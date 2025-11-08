<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentToken;

/**
 * @property null|string $paymentToken
 */
trait PaymentTokenGetterTrait
{
    public function getPaymentToken(): ?string
    {
        return $this->paymentToken;
    }
}
