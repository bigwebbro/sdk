<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Amount;

use Symfony\Component\Validator\Constraints as Assert;

trait AmountTrait
{
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d{1,15}(\.\d{1,2})?$/',
        message: 'The amount must be a number with no more than 15 digits before the decimal point and 1–2 digits after it.'
    )]
    #[Assert\GreaterThanOrEqual(value: 0.01, message: 'Min amount — 0.01')]
    #[Assert\LessThanOrEqual(value: 999999999999999.99, message: 'Max amount — 999 999 999 999 999.99')]
    private string $amount;
}
