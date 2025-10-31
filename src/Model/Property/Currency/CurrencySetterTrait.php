<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Currency;

/**
 * @property string $currency
 */
trait CurrencySetterTrait
{
    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
