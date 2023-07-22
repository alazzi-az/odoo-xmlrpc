<?php
use Pest\TestSuite;
use AlazziAz\OdooXmlrpc\DTO\CallParamsDTO;
use AlazziAz\OdooXmlrpc\Enums\OperationMethods;

it('creates a CallParamsDTO instance with the correct properties', function () {
    $model = 'res.partner';
    $method = OperationMethods::Create;
    $args = ['name' => 'John', 'age' => 25];
    $fields = ['name', 'age'];
    $limit = 10;
    $offset = 5;

    $callParams = new CallParamsDTO($model, $method, $args, $fields, $limit, $offset);

    expect($callParams->model)->toBe($model);
    expect($callParams->method)->toBe($method);
    expect($callParams->args)->toBe($args);
    expect($callParams->fields)->toBe($fields);
    expect($callParams->limit)->toBe($limit);
    expect($callParams->offset)->toBe($offset);
});

it('converts the CallParamsDTO instance to an array', function () {
    $model = 'res.partner';
    $method = OperationMethods::Read;
    $args = [['id', '=', 1]];
    $fields = ['name', 'age'];
    $limit = 10;
    $offset = null;

    $callParams = new CallParamsDTO($model, $method, $args, $fields, $limit, $offset);

    $expectedArray = [
        $model,
        $method->value,
        $args,
        ['fields' => $fields, 'limit' => $limit],
    ];

    expect($callParams->toArray())->toBe($expectedArray);
});


