<?php

use AlazziAz\OdooXmlrpc\QueryBuilder;
use Laminas\XmlRpc\Client;

it('has model as query builder', function () {
    $odoo = getClient();

    expect($odoo->model('res.partner'))->toBeInstanceOf(QueryBuilder::class);
});

it('has common object', function () {
    $odoo = getClient();

    expect($odoo->getCommonClient())->toBeInstanceOf(Client::class);
});

it('has object object', function () {
    $odoo = getClient();

    expect($odoo->getObjectClient())->toBeInstanceOf(Client::class);
});

it('call method works', function () {
    $odoo = mockClient();

    $result = $odoo->call([
        'res.partner', 'search_read', [], ['limit' => 5],
    ]);
    expect($result)->toBeArray();
});

it('get method works', function () {
    $odoo = mockClient();

    $result = $odoo->get(
        model: 'res.partner',
        filters: [],
        fields: ['name', 'id'],
        limit: 5
    );

    expect($result)->toBeArray();

});
test('search method works', function () {
    $odoo = mockClient();

    $result = $odoo->search(model: 'res.partner', filters: []);

    expect($result)->toBeArray();

    return $result;
});

it('read method works', function ($searchResult) {
    $odoo = mockClient();

    $result = $odoo->read(model: 'res.partner', ids: $searchResult, fields: ['name', 'id']);

    expect($result)->toBeArray();
})->depends('search method works');

test('create method works', function () {
    $odoo = mockClient(5);

    $result = $odoo->create('res.partner', [
        'name' => 'Test Partner',
        'email' => 'test@test.com']);

    expect($result)->toBeInt();

    return $result;
});

it('update method works', function ($createResult) {
    $odoo = mockClient(true);
    $result = $odoo->update('res.partner', [$createResult], [
        'name' => 'Test Partner',
        'email' => 'test@test.com']);

    expect($result)->toBeIn([true, false, 1, 0]);
})->depends('create method works');

it('delete method works', function ($createResult) {
    $odoo = mockClient(true);

    $result = $odoo->delete('res.partner', [$createResult]);

    expect($result)->toBeIn([true, false, 1, 0]);
})->depends('create method works');

it('count method works', function () {
    $odoo = mockClient(1);

    $result = $odoo->count('res.partner', []);

    expect($result)->toBeInt();
});

it('getUid method works', function () {
    $odoo = mockClient();

    $result = $odoo->getUid();

    expect($result)->toBeInt();
});

it('fetch getVersion method ', function () {
    $odoo = mockClient([], '1.1.1');

    $result = $odoo->getVersion();

    $result = is_array($result) ? $result['server_serie'] : $result;

    expect($result)->toBeString($result);
});
