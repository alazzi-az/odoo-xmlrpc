<?php

ini_set('memory_limit', '-1');

use AlazziAz\OdooXmlrpc\Contracts\OdooClientContract;
use AlazziAz\OdooXmlrpc\Odoo;
use AlazziAz\OdooXmlrpc\Testing\OdooClientFake;

final class ConnectionDTO
{
    public function __construct(
        public string $uri,
        public string $suffix,
        public string $db,
        public string $username,
        public string $password,
        public bool $realConnection = false
    ) {
    }
}

function getConnectionDTO(): ConnectionDTO
{
    return new ConnectionDTO(
        uri: 'http://foo',
        suffix: 'xmlrpc/2',
        db: 'foo',
        username: 'foo',
        password: 'foo'
    );
}

function getClient(): OdooClientContract
{
    $connectionDto = getConnectionDTO();

    return Odoo::client(
        url: $connectionDto->uri,
        suffix: $connectionDto->suffix,
        db: $connectionDto->db,
        username: $connectionDto->username,
        password: $connectionDto->password
    );
}

function mockClient(mixed $fakeResponse = [], mixed $fakeCommon = 1): OdooClientContract
{
    $connectionDto = getConnectionDTO();
    if ($connectionDto->realConnection) {
        return getClient();
    }

    return new OdooClientFake(
        url: $connectionDto->uri,
        suffix: $connectionDto->suffix,
        db: $connectionDto->db,
        username: $connectionDto->username,
        password: $connectionDto->password,
        fakeObjectResponse: $fakeResponse,
        fakeCommonResponse: $fakeCommon
    );
}
