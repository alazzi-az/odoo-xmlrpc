<?php

namespace AlazziAz\OdooXmlrpc\Testing;

use AlazziAz\OdooXmlrpc\Concern\Filterable;
use AlazziAz\OdooXmlrpc\Contracts\OdooClientContract;
use AlazziAz\OdooXmlrpc\DTO\CallParamsDTO;
use AlazziAz\OdooXmlrpc\Enums\EndPoints;
use AlazziAz\OdooXmlrpc\Enums\OperationMethods;
use AlazziAz\OdooXmlrpc\QueryBuilder;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Client\Adapter\Test;
use Laminas\Http\Client as HttpClient;
use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Response;

class OdooClientFake implements OdooClientContract
{
    use Filterable;

    private readonly Client $commonClient;

    private readonly Client $objectClient;

    protected AdapterInterface $httpObjectAdapter;

    protected AdapterInterface $httpCommonAdapter;

    protected HttpClient $httpObjectClient;

    protected HttpClient $httpCommonClient;

    public function __construct(
        protected mixed $fakeObjectResponse = [],
        protected mixed $fakeCommonResponse = 1,
        private readonly string $url = 'http:/foo',
        private readonly string $suffix = 'foo',
        private readonly string $db = 'foo',
        private readonly string $username = 'foo',
        private readonly string $password = 'foo',
    ) {
        $this->httpObjectAdapter = new Test;
        $this->httpObjectClient = new HttpClient(
            $url,
            ['adapter' => $this->httpObjectAdapter]
        );

        $this->httpCommonAdapter = new Test;
        $this->httpCommonClient = new HttpClient(
            $url,
            ['adapter' => $this->httpCommonAdapter]
        );

        $this->commonClient = new Client(EndPoints::Common->getFullUrl($url, $suffix), $this->httpCommonClient);
        $this->objectClient = new Client(EndPoints::Object->getFullUrl($url, $suffix), $this->httpObjectClient);

        $this->setObjectResponseTo($this->fakeObjectResponse);
        $this->setCommonResponseTo($this->fakeCommonResponse);
    }

    public function get(string $model, array $filters = [], array $fields = [], ?int $limit = null, ?int $offset = null, ?string $order = null, ?array $context = []): array
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
            order: $order,
            context: $context
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

    public function update(string $model, int|array $ids, array $data, array $context = []): ?int
    {
        $params = new CallParamsDTO(
            model: $model,
            method: OperationMethods::Write,
            args: [$ids, $data],
            context: $context,
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

    public function setObjectResponseTo(mixed $nativeVars): void
    {
        $response = $this->getServerResponseFor($nativeVars);
        $this->httpObjectAdapter->setResponse($response);
    }

    public function setCommonResponseTo(mixed $nativeVars): void
    {
        $response = $this->getServerResponseFor($nativeVars);
        $this->httpCommonAdapter->setResponse($response);
    }

    public function getServerResponseFor(mixed $nativeVars): string
    {
        $response = new Response;
        $response->setReturnValue($nativeVars);
        $xml = $response->saveXml();

        return $this->makeHttpResponseFrom($xml);
    }

    public function makeHttpResponseFrom(string $data, int $status = 200, string $message = 'OK'): string
    {
        $headers = [
            "HTTP/1.1 $status $message",
            "Status: $status",
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: '.strlen($data),
        ];

        return implode("\r\n", $headers)."\r\n\r\n$data\r\n\r\n";
    }
}
