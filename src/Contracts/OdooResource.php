<?php

namespace AlazziAz\OdooXmlrpc\Contracts;


interface OdooResource
{
    public static function getModelName(): string;

    public static function getModelFields(): array;

}