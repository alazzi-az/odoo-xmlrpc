<?php

namespace AlazziAz\OdooXmlrpc\Contracts;

use AlazziAz\OdooXmlrpc\QueryBuilder;
use Laminas\XmlRpc\Client;

interface OdooClientContract
{
    public function get(string $model, array $filters = [], array $fields = [], int $limit = null, int $offset = null): array;

    public function call(array $params): array|int|null;

    public function getUid(): int;

    public function read(string $model, array $ids, array $fields = []): ?array;

    public function search(string $model, array $filters = []): ?array;

    public function create(string $model, array $data): int|array;

    public function update(string $model, int|array $ids, array $data): ?int;

    public function delete(string $model, int|array $ids): ?int;

    public function count(string $model, array $filters = []): ?int;

    public function model(string $model): QueryBuilder;

    public function getVersion(): mixed;

    public function getCommonClient(): Client;

    public function getObjectClient(): Client;
}
