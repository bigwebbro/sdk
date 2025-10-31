<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Currency;

/**
 * @property string $currency
 */
trait CurrencyGetterTrait
{
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
