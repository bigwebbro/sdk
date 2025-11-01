<?php

declare(strict_types=1);

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
