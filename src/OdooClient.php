<?php

namespace AlazziAz\OdooXmlrpc;

use AlazziAz\OdooXmlrpc\Concern\Filterable;
use AlazziAz\OdooXmlrpc\Contracts\OdooClientContract;
use AlazziAz\OdooXmlrpc\DTO\CallParamsDTO;
use AlazziAz\OdooXmlrpc\Enums\OperationMethods;
use Laminas\XmlRpc\Client;

class OdooClient implements OdooClientContract
{
    use Filterable;

    public function __construct(
        private readonly Client $commonClient,
        private readonly Client $objectClient,
        private readonly string $db,
        private readonly string $username,
        private readonly string $password,
    ) {
    }

    public function get(string $model, array $filters = [], array $fields = [], int $limit = null, int $offset = null): array
    {
        $filters = $this->prepareFilters($filters);
        $fields = $this->prepareFields($fields);

        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::SearchRead,
            args: [$filters],
            fields: $fields,
            limit: $limit,
            offset: $offset,
        );

        return $this->call($params->toArray());

    }

    public function call(array $params): array|int|null
    {

        $params = array_merge([
            $this->db,
            $this->getUid(),
            $this->password,
        ], $params);

        return $this->objectClient->call('execute_kw', $params);
    }

    public function getUid(): int
    {
        $params = [
            'db' => $this->db,
            'login' => $this->username,
            'password' => $this->password,
        ];

        return $this->commonClient->call('login', $params);
    }

    public function read(string $model, array $ids, array $fields = []): ?array
    {
        $fields = $this->prepareFields($fields);

        $ids = count($ids) > 1 ? [$ids] : $ids;

        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Read,
            args: $ids,
            fields: $fields,
        );

        return $this->call($params->toArray());
    }

    public function search(string $model, array $filters = []): ?array
    {
        $filters = $this->prepareFilters($filters);

        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Search,
            args: [$filters],
        );

        //        the following steps to make test work because we need to mock integer value
        $result = $this->call($params->toArray());

        return is_array($result) ? $result : [$result];
    }

    public function create(string $model, array $data): int|array
    {
        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Create,
            args: [$data],
        );

        return $this->call($params->toArray());
    }

    public function update(string $model, int|array $ids, array $data): ?int
    {
        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Write,
            args: [$ids, $data],
        );

        return $this->call($params->toArray());
    }

    public function delete(string $model, int|array $ids): ?int
    {
        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Unlink,
            args: [$ids],
        );

        return $this->call($params->toArray());
    }

    public function count(string $model, array $filters = []): ?int
    {
        $filters = $this->prepareFilters($filters);

        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::SearchCount,
            args: [$filters],
        );

        return $this->call($params->toArray());
    }

    public function model(string $model): QueryBuilder
    {
        return new QueryBuilder($model, $this);
    }

    public function getVersion(): mixed
    {
        return $this->commonClient->call('version');
    }

    public function getCommonClient(): Client
    {
        return $this->commonClient;
    }

    public function getObjectClient(): Client
    {
        return $this->objectClient;
    }
}
