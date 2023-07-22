<?php
namespace Concern;

use AlazziAz\OdooXmlrpc\OdooClient;
use AlazziAz\OdooXmlrpc\QueryBuilder;


beforeEach(function () {
    $this->odooClient = mockClient();
    $this->testClass=new class  {
        use \AlazziAz\OdooXmlrpc\Concern\Resourceable;
        public static function getModelName(): string {
            return  'res.partner';
        }

        public static function getModelFields():array{
            return ['name','id'];
        }
    };
    $this->testClass::boot($this->odooClient);
});

it('lists records', function () {

    $result =  $this->testClass::list();
    expect($result)->toBeArray();
});

test('create record', function () {
    $this->odooClient=mockClient(1);
    $this->testClass::boot($this->odooClient);

    $result = $this->testClass::create([
        'name' => 'Test Partner',
        'email' => 'test@test.com'
    ]);

    expect($result)->toBeInt();
    return $result;
});
it('finds a record', function ($createResult) {


    $result =  $this->testClass::find($createResult);

    expect($result)->toBeArray();
})->depends('create record');

it('update record', function ($createResult) {
    $this->odooClient = mockClient(true);
    $this->testClass::boot($this->odooClient);

    $result = $this->testClass::update( $createResult, [
        'name' => 'Test Partner',
        'email' => 'test@test.com'
    ]);

    expect($result)->toBeIn([true,false,1,0]);
})->depends('create record');

it('delete record', function ($createResult) {
    $this->odooClient = mockClient(true);
    $this->testClass::boot($this->odooClient);
    $result = $this->testClass::delete( $createResult);
    expect($result)->toBeIn([true,false,1,0]);
})->depends('create record');

it('count record', function () {
    $this->odooClient = mockClient(1);
    $this->testClass::boot($this->odooClient);
    $result = $this->testClass::count();
    expect($result)->toBeInt();
});

test('search records', function () {


    $result = $this->testClass::search( filters: []);

    expect($result)->toBeArray();
    return $result;
});

it('read records', function ($searchResult) {


    $result = $this->testClass::read(ids: $searchResult, fields: ['name', 'id']);

    expect($result)->toBeArray();
})->depends('search records');

it(' get query object', function () {

    $result = $this->testClass::query();
    expect($result)->toBeInstanceOf(QueryBuilder::class);
});
// Continue with similar tests for create, update, delete, count, read, and query methods
