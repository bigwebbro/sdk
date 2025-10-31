<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Email;

use Symfony\Component\Validator\Constraints as Assert;

trait CustomerEmailTrait
{
    #[Assert\Email]
    protected string $customerEmail;
}