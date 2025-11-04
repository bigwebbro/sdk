<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Currency;

use Tiyn\MerchantApiSdk\Validator\CurrencyConstraint as AssertCurrency;

trait CurrencyTrait
{
    #[AssertCurrency]
    private ?string $currency = null;
}
