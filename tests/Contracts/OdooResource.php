<?php

use AlazziAz\OdooXmlrpc\Contracts\OdooResource;

it('implements the OdooResource interface', function () {
    $testClass = new class implements OdooResource
    {
        public static function getModelName(): string
        {
            return 'res.partner';
        }

        public static function getModelFields(): array
        {
            return ['name', 'age', 'country'];
        }
    };

    expect($testClass)->toBeInstanceOf(OdooResource::class);
});

it('returns the correct model name', function () {
    $testClass = new class implements OdooResource
    {
        public static function getModelName(): string
        {
            return 'res.partner';
        }

        public static function getModelFields(): array
        {
            return ['name', 'age', 'country'];
        }
    };

    $modelName = $testClass::getModelName();

    expect($modelName)->toBe('res.partner');
});

it('returns the correct model fields', function () {
    $testClass = new class implements OdooResource
    {
        public static function getModelName(): string
        {
            return 'res.partner';
        }

        public static function getModelFields(): array
        {
            return ['name', 'age', 'country'];
        }
    };

    $modelFields = $testClass::getModelFields();

    expect($modelFields)->toBe(['name', 'age', 'country']);
});
