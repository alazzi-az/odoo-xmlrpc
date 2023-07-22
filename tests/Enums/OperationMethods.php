<?php
use Pest\TestSuite;
use AlazziAz\OdooXmlrpc\Enums\OperationMethods;

it('returns the correct operation method value', function () {
    $searchRead = OperationMethods::SearchRead;
    $create = OperationMethods::Create;
    $write = OperationMethods::Write;
    $unlink = OperationMethods::Unlink;
    $read = OperationMethods::Read;
    $search = OperationMethods::Search;
    $searchCount = OperationMethods::SearchCount;
    $invoiceOpen = OperationMethods::InvoiceOpen;
    $actionRegisterPayment = OperationMethods::ActionRegisterPayment;
    $actionPost = OperationMethods::ActionPost;
    $buttonDraft = OperationMethods::ButtonDraft;
    $createPayments = OperationMethods::CreatePayments;
    $actionCreatePayments = OperationMethods::ActionCreatePayments;
    $actionInvoicePaid = OperationMethods::ActionInvoicePaid;

    expect($searchRead->value)->toBe('search_read');
    expect($create->value)->toBe('create');
    expect($write->value)->toBe('write');
    expect($unlink->value)->toBe('unlink');
    expect($read->value)->toBe('read');
    expect($search->value)->toBe('search');
    expect($searchCount->value)->toBe('search_count');
    expect($invoiceOpen->value)->toBe('action_invoice_open');
    expect($actionRegisterPayment->value)->toBe('action_register_payment');
    expect($actionPost->value)->toBe('action_post');
    expect($buttonDraft->value)->toBe('button_draft');
    expect($createPayments->value)->toBe('create_payments');
    expect($actionCreatePayments->value)->toBe('action_create_payments');
    expect($actionInvoicePaid->value)->toBe('action_invoice_paid');
});
