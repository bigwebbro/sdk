<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Enum\PaymentStatusEnum;

final class Status
{
    /**
     * @phpstan-ignore-next-line
     */
    private PaymentStatusEnum $name;

    public function getName(): PaymentStatusEnum
    {
        return $this->name;
    }
}
