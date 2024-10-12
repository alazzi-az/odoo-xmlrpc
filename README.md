# Odoo XML-RPC Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alazzi-az/odoo-xmlrpc.svg?style=flat-square)](https://packagist.org/packages/alazzi-az/odoo-xmlrpc)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/alazzi-az/odoo-xmlrpc/run-tests?label=tests)](https://github.com/alazzi-az/odoo-xmlrpc/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/alazzi-az/odoo-xmlrpc/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/alazzi-az/odoo-xmlrpc/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alazzi-az/odoo-xmlrpc.svg?style=flat-square)](https://packagist.org/packages/alazzi-az/odoo-xmlrpc)

---
The **Odoo XML-RPC Client** is a PHP package that provides a simple and easy-to-use interface for interacting with the Odoo XML-RPC API. Unlike other Odoo clients that require extensions or other dependencies, this client uses the laminas/laminas-xmlrpc package, which is a pure PHP implementation of the XML-RPC protocol.

## Requirements

- PHP 8.1 or later
- The laminas/laminas-xmlrpc package

## Installation
You can install the package via composer:

```bash
composer require alazzi-az/odoo-xmlrpc
```

## Usage
To use the Odoo XML-RPC Client, first create an instance of the OdooClient class:
```php
use AlazziAz\OdooXmlrpc\Odoo;

$client = Odoo::client('https://your-odoo-instance.com','xmlrpc/2', 'database', 'username', 'password');
```
Replace https://your-odoo-instance.com, database, username, and password with the appropriate values for your Odoo instance.

### Then you can use Odoo Client
```php
use AlazziAz\OdooXmlrpc\Client;

// Call a method on the Odoo server
$result = $client->call('res.partner', 'search_read', [], ['limit' => 5]);

// Get records from a model
$records = $client->get('res.partner', [], ['name', 'id'], 5);

// Search for records in a model
$searchResult = $client->search('res.partner', []);

// Read records from a model
$records = $client->read('res.partner', $searchResult, ['name', 'id']);

// Create a new record in a model
$id = $client->create('res.partner', [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com'
]);

// Update an existing record in a model
$result = $client->update('res.partner', [$id], [
    'name' => 'Jane Doe',
    'email' => 'janedoe@example.com'
]);

// Delete a record from a model
$result = $client->delete('res.partner', [$id]);

// Get the number of records in a model
$count = $client->count('res.partner', []);

// Get the current user's ID
$uid = $client->getUid();

// Get the version of the Odoo server
$version = $client->getVersion();

```
### Use Query Builder 
- To create a new instance of the QueryBuilder class, you need to provide the name of the model to query and an instance of the OdooClient class or using model method in client object
    ```php
    // Create a new instance of the QueryBuilder using the model method
    $queryBuilder = $client->model('res.partner');
    
    // Or create a new instance of the QueryBuilder using the constructor
    $queryBuilder = new QueryBuilder('res.partner', $client);
    ```
- And here is usage examples:  
```php
// Query with difference conditions cluose
    $result = $queryBuilder
      ->where('id', '=', 5)
      ->orWhere('id', '=', 6)
      ->whereIn('id', [11, 10])
      ->whereNotIn('id', [100, 200])
      ->whereNull('id')
      ->whereNotNull('id')
      ->whereBetween('id', [10,99])
      ->whereNotBetween('id', [500, 600])
      ->whereNotBetween('id', [100, 200])
      ->get();

// You can provide multiple arguments to select multiple fields
   $result = $queryBuilder->select('id', 'name')->get();

// retrieve the first record that matches the query.
   $result = $queryBuilder->first();

//  limit the number of records returned by the query.
   $result = $queryBuilder->limit(5)->get();

//  sort the records returned by the query.
   $result = $queryBuilder->order('name')->get();

// retrieve the records that match the query. It returns an array of records
   $records = $queryBuilder->where('name', 'ilike', 'johndoe')
                    ->get();

// retrieve the number of records that match the query:
   $result = $queryBuilder->count();

// retrieve a record by its ID. You need to provide the ID as the first argument
   $result = $queryBuilder->find($createResult);

// create a new record. You need to provide an array of data to create the record
   $result = $queryBuilder->create([
        'name' => 'test',
        'email' => 't@t.t']
     );

//  update one or more records. You need to provide an array of data to update the records
   $result = $queryBuilder->where('id', '=', 4)->update([
        'name' => 'test2'
        ]);

// retrieve the IDs of the records that match the query
   $result = $queryBuilder->ids();

// delete the records that match the query
   $result = $queryBuilder->where('id', '=', 5)->delete();

```
### Creating a Class for Model
To create a class for a model, you can follow this example:

```php
namespace Your\Namespace;

use AlazziAz\OdooXmlrpc\OdooClient;
use AlazziAz\OdooXmlrpc\QueryBuilder;
use AlazziAz\OdooXmlrpc\Concern\Resourceable;

class OdooPartner implements \AlazziAz\OdooXmlrpc\Interfaces\OdooResource
{
    use Resourceable;
  
    public static function getModelName(): string
    {
        return 'res.partner';
    }

    public static function getModelFields(): array
    {
        return ['name', 'email'];
    }
}

// To use the class, you need to boot it:
OdooPartner::boot($odooClient);


// Now you can use the class to perform CRUD operations:
$partners = OdooPartner::query()->get();
foreach ($partners as $partner) {
    echo $partner['name'] . ': ' . $partner['email'] . "\n";
}

$newPartnerId = OdooPartner::create([
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
]);

OdooPartner::update($newPartnerId, [
    'name' => 'John Doe Jr.',
    'email' => 'john.doe@example.com',
]);

OdooPartner::delete($newPartnerId);

$partnerCount = OdooPartner::count();

$searchResult = OdooPartner::search([
    ['name', 'ilike', 'johndoe'],
    ['email', 'ilike', 'john.doe@example.com'],
]);

$partners = OdooPartner::read($searchResult, ['name', 'email']);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/alazzi-az/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mohammed Ali Azman](https://github.com/alazzi-az)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

