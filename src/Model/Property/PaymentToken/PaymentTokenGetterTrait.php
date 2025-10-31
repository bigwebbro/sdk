<?php

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentToken;

/**
 * @property string $paymentToken
 */
trait PaymentTokenGetterTrait
{
    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }
}
