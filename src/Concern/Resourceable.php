<?php

namespace AlazziAz\OdooXmlrpc\Concern;

use AlazziAz\OdooXmlrpc\Contracts\OdooClientContract;
use AlazziAz\OdooXmlrpc\Contracts\OdooResource;
use AlazziAz\OdooXmlrpc\OdooClient;
use AlazziAz\OdooXmlrpc\QueryBuilder;

/**
 * @mixin OdooResource
 * */
trait Resourceable
{
    protected static OdooClientContract $odooClient;

    public static function boot(OdooClientContract $odooClient): void
    {
        self::$odooClient = $odooClient;
    }

    public static function list(array $filters = [], array $fields = []): array
    {
        return self::$odooClient->get(self::getModelName(), $filters, $fields);
    }

    public static function find(int $id): array
    {
        return self::$odooClient->get(
            model: self::getModelName(),
            filters: [['id', '=', $id]],
            fields: self::getModelFields(),
        );
    }

    public static function create(array $data): int
    {
        return self::$odooClient->create(
            model: self::getModelName(),
            data: $data,
        );
    }

    public static function update(int $id, array $data): int
    {
        return self::$odooClient->update(
            model: self::getModelName(),
            ids: $id,
            data: $data,
        );
    }

    /**
     * @throws \Exception
     */
    public static function delete(int $id): int
    {
        return self::$odooClient->delete(
            model: self::getModelName(),
            ids: $id,
        );
    }

    public static function count(array $filters = []): int
    {
        return self::$odooClient->count(
            model: self::getModelName(),
            filters: $filters,
        );
    }

    public static function read(array $ids, array $fields = []): array
    {
        return self::$odooClient->read(
            model: self::getModelName(),
            ids: $ids,
            fields: $fields,
        );
    }

    public static function search(array $filters = []): array
    {
        return self::$odooClient->search(
            model: self::getModelName(),
            filters: $filters,
        );
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(self::getModelName(), self::$odooClient);
    }
}
