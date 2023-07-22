<?php

use AlazziAz\OdooXmlrpc\Odoo;
use AlazziAz\OdooXmlrpc\OdooClient;

it('may create a client', function () {
    $connectionDto = getConnectionDTO();
    $odoo = Odoo::client(
        url: $connectionDto->uri,
        suffix: $connectionDto->suffix,
        db: $connectionDto->db,
        username: $connectionDto->username,
        password: $connectionDto->password
    );

    expect($odoo)->toBeInstanceOf(OdooClient::class);
});
