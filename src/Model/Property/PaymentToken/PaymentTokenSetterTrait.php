<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentToken;

/**
 * @property string $paymentToken
 */
trait PaymentTokenSetterTrait
{
    public function setPaymentToken(string $paymentToken): static
    {
        $this->paymentToken = $paymentToken;

        return $this;
    }
}
