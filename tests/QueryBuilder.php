<?php

// Path: tests\QueryBuilder.php
// check all tests we can make for this class by pestphp syntax


use AlazziAz\OdooXmlrpc\QueryBuilder;

it('can be created', function () {
    $queryBuilder = new QueryBuilder('res.partner', getClient());

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});

test('can create new model', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient(1));

    $result = $queryBuilder->create([
        'name' => 'test',
        'email' => 't@t.t']
    );

    expect($result)->toBeInt();
    return $result;
});

test('can update model', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient(1));

    $result = $queryBuilder->where('id', '=', $createResult)->update([
        'name' => 'test2'
    ]);

    expect($result)->toBeInt();
})->depends('can create new model');


test('can delete model', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient(1));

    $result = $queryBuilder->where('id', '=', $createResult)->delete();

    expect($result)->toBeInt();
})->depends('can create new model');


test('can query with where', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->where('id', '=', $createResult)->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with where and orWhere', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder
        ->where('id', '=', $createResult)
        ->orWhere('id', '=', $createResult)
        ->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with whereIn', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereIn('id', [$createResult,$createResult])->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with whereNotIn', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereNotIn('id', [$createResult, $createResult])->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with whereNull', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereNull('name')->get();

    expect($result)->toBeArray();
});

test('can query with whereNotNull', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereNotNull('id')->get();

    expect($result)->toBeArray();
});
test('can query with whereBetween', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereBetween('id', [$createResult, $createResult])->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with whereNotBetween', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->whereNotBetween('id', [$createResult,$createResult])->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can query with where and orWhere and whereIn and whereNotIn and whereNull and
 whereNotNull and whereBetween and whereNotBetween', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder
        ->where('id', '=', $createResult)
        ->orWhere('id', '=', $createResult)
        ->whereIn('id', [$createResult, $createResult])
        ->whereNotIn('id', [$createResult+1, $createResult+2])
        ->whereNull('id')
        ->whereNotNull('id')
        ->whereBetween('id', [--$createResult, ++$createResult])
        ->whereNotBetween('id', [$createResult+1, $createResult+2])
        ->whereNotBetween('id', [100, 200])
        ->get();

    expect($result)->toBeArray();
})->depends('can create new model');

test('can limit', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->limit(5)->get();

    expect($result)->toBeArray();
});

test('can get first', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient([[]]));

    $result = $queryBuilder->first();

    expect($result)->toBeArray();
});

test('can find', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->find($createResult);

    expect($result)->toBeArray();
})->depends('can create new model');

test('can get count', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient(1));

    $result = $queryBuilder->count();

    expect($result)->toBeInt();
});


test('can select fields', function ($createResult) {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->select('id', 'name')->find($createResult);

    expect($result)->toBeArray();
})->depends('can create new model');

test('can get ids', function () {
    $queryBuilder = new QueryBuilder('res.partner', mockClient());

    $result = $queryBuilder->ids();

    expect($result)->toBeArray();
});











