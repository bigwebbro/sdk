<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Amount;

/**
 * @property string $amount
 */
trait AmountSetterTrait
{
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}