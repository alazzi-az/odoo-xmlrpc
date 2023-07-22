<?php
ini_set('memory_limit', '-1');

use AlazziAz\OdooXmlrpc\Enums\EndPoints;
use AlazziAz\OdooXmlrpc\Odoo;
use AlazziAz\OdooXmlrpc\OdooClient;
use Laminas\Http\Client\Adapter\Test;
use Laminas\XmlRpc\Client;


final class ConnectionDTO
{

    public function __construct(
        public string $uri,
        public string $suffix,
        public string $db,
        public string $username,
        public string $password,
        public bool   $realConnection = false
    )
    {
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


function getClient(): OdooClient
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


function mockClient(mixed $fakeResponse = [], mixed $fakeCommon = 1): OdooClient
{
    $connectionDto = getConnectionDTO();
    if ($connectionDto->realConnection)
        return getClient();

    $httpObjectClient = getHttpClient($connectionDto->uri, $fakeResponse);
    $httpCommonClient = getHttpClient($connectionDto->uri, $fakeCommon);

    $commonClient = new Client(EndPoints::Common->getFullUrl($connectionDto->uri, $connectionDto->suffix), $httpCommonClient);
    $objectClient = new Client(EndPoints::Object->getFullUrl($connectionDto->uri, $connectionDto->suffix), $httpObjectClient);
    return new OdooClient(
        commonClient: $commonClient,
        objectClient: $objectClient,
        db: $connectionDto->db,
        username: $connectionDto->username,
        password: $connectionDto->password,
    );
}

/**
 * @param string $url
 * @param mixed $fakeResponse
 * @return \Laminas\Http\Client
 */
function getHttpClient(string $url, mixed $fakeResponse): \Laminas\Http\Client
{
    $httpAdapter = new Test();
    $httpClient = new \Laminas\Http\Client(
        $url,
        ['adapter' => $httpAdapter]
    );

    $response = getServerResponseFor($fakeResponse);
    $httpAdapter->setResponse($response);
    return $httpClient;
}

function getServerResponseFor($nativeVars): string
{
    $response = new Laminas\XmlRpc\Response();
    $response->setReturnValue($nativeVars);
    $xml = $response->saveXml();

    return makeHttpResponseFrom($xml);
}

function makeHttpResponseFrom($data, $status = 200, $message = 'OK'): string
{
    $headers = [
        "HTTP/1.1 $status $message",
        "Status: $status",
        'Content-Type: text/xml; charset=utf-8',
        'Content-Length: ' . strlen($data),
    ];
    return implode("\r\n", $headers) . "\r\n\r\n$data\r\n\r\n";
}