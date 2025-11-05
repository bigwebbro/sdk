<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment\Enum;

enum PaymentStatusEnum: string
{
    case PaymentCreated = 'PaymentCreated';
    case PaymentTransactionCreated = 'PaymentTransactionCreated';
    case PaymentPaid = 'PaymentPaid';
    case PaymentFailed = 'PaymentFailed';
    case PaymentRefunded = 'PaymentRefunded';
    case PaymentCancelled = 'PaymentCancelled';
}
