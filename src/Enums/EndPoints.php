<?php

namespace AlazziAz\OdooXmlrpc\Enums;

enum EndPoints: string
{
    case Common = 'common';
    case Object = 'object';
    case Report = 'report';

    public function getFullUrl(
        string $url ,
        string $suffix
    ): string
    {
        return $url.'/'.$suffix.'/'.$this->value;
    }
}
