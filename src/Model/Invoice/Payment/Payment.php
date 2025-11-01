<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

use Tiyn\MerchantApiSdk\Model\Invoice\Status;
use Tiyn\MerchantApiSdk\Model\Property\PaymentMethod\PaymentMethodGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentMethod\PaymentMethodTrait;

final class Payment
{
    use PaymentMethodTrait;
    use PaymentMethodGetterTrait;

    private Details $details;

    private Status $status;

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}