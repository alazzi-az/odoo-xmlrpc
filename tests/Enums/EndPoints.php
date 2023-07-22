<?php
use Pest\TestSuite;
use AlazziAz\OdooXmlrpc\Enums\EndPoints;

it('returns the full URL', function () {
    $url = 'https://example.com';
    $suffix = 'api';

    $commonEndpoint = EndPoints::Common;
    $objectEndpoint = EndPoints::Object;
    $reportEndpoint = EndPoints::Report;

    $commonUrl = $commonEndpoint->getFullUrl($url, $suffix);
    $objectUrl = $objectEndpoint->getFullUrl($url, $suffix);
    $reportUrl = $reportEndpoint->getFullUrl($url, $suffix);

    expect($commonUrl)->toBe( $url.'/'.$suffix.'/'.$commonEndpoint->value);
    expect($objectUrl)->toBe($url.'/'.$suffix.'/'.$objectEndpoint->value);
    expect($reportUrl)->toBe($url.'/'.$suffix.'/'.$reportEndpoint->value);
});
