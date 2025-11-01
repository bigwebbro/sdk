<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Currency;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Configuration\Validation\CurrencyConstraint as AssertCurrency;

trait CurrencyTrait
{
    #[Assert\NotBlank]
    #[AssertCurrency]
    protected string $currency;
}
