<?php

namespace AlazziAz\OdooXmlrpc;

use AlazziAz\OdooXmlrpc\Contracts\OdooClientContract;
use AlazziAz\OdooXmlrpc\Enums\EndPoints;
use Laminas\XmlRpc\Client;

class Odoo
{
    /**
     * Creates a new Odoo Client instance.
     */
    public static function client(string $url,string $suffix, string $db, string $username, string $password): OdooClientContract
    {

        $commonClient = new Client(EndPoints::Common->getFullUrl($url, $suffix));
        $objectClient = new Client(EndPoints::Object->getFullUrl($url, $suffix));

        return new OdooClient(
            commonClient: $commonClient,
            objectClient: $objectClient,
            db: $db,
            username: $username,
            password: $password,
        );
    }
}