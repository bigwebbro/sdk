<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\PaymentLink;

/**
 * @property string $paymentLink
 */
trait PaymentLinkGetterTrait
{
    public function getPaymentLink(): string
    {
        return $this->paymentLink;
    }
}
