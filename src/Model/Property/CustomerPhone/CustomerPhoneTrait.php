<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerPhone;

use Symfony\Component\Validator\Constraints as Assert;

trait CustomerPhoneTrait
{
    #[Assert\Regex(
        pattern: '/^\+7\d{10}$/',
        message: 'The phone number must be in the format +7xxxxxxxxxx (10 digits after +7).'
    )]
    private ?string $customerPhone = null;
}
