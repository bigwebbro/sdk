<?php

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentMethod;

/**
 * @property string $paymentMethod
 */
trait PaymentMethodGetterTrait
{
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
}
