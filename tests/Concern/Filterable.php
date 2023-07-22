<?php

use AlazziAz\OdooXmlrpc\Concern\Filterable;

it('prepares filters correctly', function () {
    $filterable = new class
    {
        use Filterable;

        public function testPrepareFilters(array $filters): array
        {
            return $this->prepareFilters($filters);
        }
    };

    $filters = [
        'name' => 'John',
        'age' => ['>=', 18],
        'country' => 'USA',
    ];

    $preparedFilters = $filterable->testPrepareFilters($filters);

    expect($preparedFilters)->toBe([
        ['name', '=', 'John'],
        ['age', '>=', 18],
        ['country', '=', 'USA'],
    ]);
});

it('checks if operator is valid', function () {
    $filterable = new class
    {
        use Filterable;

        public function testIsOperator(string $operator): bool
        {
            return $this->isOperator($operator);
        }
    };

    $validOperator = '=';
    $invalidOperator = 'invalid';

    expect($filterable->testIsOperator($validOperator))->toBeTrue();
    expect($filterable->testIsOperator($invalidOperator))->toBeFalse();
});

it('prepares fields correctly', function () {
    $filterable = new class
    {
        use Filterable;

        public function testPrepareFields(array $fields): array
        {
            return $this->prepareFields($fields);
        }
    };

    $fields = ['name', 'age', 'country'];

    $preparedFields = $filterable->testPrepareFields($fields);

    expect($preparedFields)->toBe(['name', 'age', 'country']);
});
