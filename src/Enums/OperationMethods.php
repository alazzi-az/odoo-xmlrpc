<?php

namespace AlazziAz\OdooXmlrpc\Enums;

enum OperationMethods: string
{
    case SearchRead = 'search_read';
    case Create = 'create';
    case Write = 'write';
    case Unlink = 'unlink';
    case Read = 'read';
    case Search = 'search';
    case SearchCount = 'search_count';
    case InvoiceOpen = 'action_invoice_open';
    case ActionRegisterPayment = 'action_register_payment';
    case ActionPost = 'action_post';
    case ButtonDraft = 'button_draft';
    case CreatePayments = 'create_payments';
    case ActionCreatePayments = 'action_create_payments';
    case ActionInvoicePaid = 'action_invoice_paid';
    case Fields = 'fields_get';
}
