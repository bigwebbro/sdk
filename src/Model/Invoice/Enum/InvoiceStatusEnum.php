<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Enum;

enum InvoiceStatusEnum: string
{
    case InvoiceCreated = 'InvoiceCreated';
    case InvoicePaymentCreated = 'InvoicePaymentCreated';
    case InvoicePaid = 'InvoicePaid';
    case InvoiceFailed = 'InvoiceFailed';
    case InvoiceRefunded = 'InvoiceRefunded';
    case InvoiceCancelled = 'InvoiceCancelled';
    case InvoicePartlyRefunded = 'InvoicePartlyRefunded';
}
