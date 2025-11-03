<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerPhone;

use Symfony\Component\Validator\Constraints as Assert;

trait CustomerPhoneTrait
{
    #[Assert\Regex(
        pattern: '/^\+7\d{10}$/',
        message: 'Номер телефона должен быть в формате +7xxxxxxxxxx (10 цифр после +7).'
    )]
    private string $customerPhone;
}
