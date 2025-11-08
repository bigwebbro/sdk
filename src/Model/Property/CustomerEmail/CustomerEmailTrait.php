<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerEmail;

use Symfony\Component\Validator\Constraints as Assert;

trait CustomerEmailTrait
{
    #[Assert\Email]
    private ?string $customerEmail = null;
}
