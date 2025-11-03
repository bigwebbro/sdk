<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentMethod;

/**
 * @property string $paymentMethod
 */
trait PaymentMethodSetterTrait
{
    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
