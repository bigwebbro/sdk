<?php

declare(strict_types=1);

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
