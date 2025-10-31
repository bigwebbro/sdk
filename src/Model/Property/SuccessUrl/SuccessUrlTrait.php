<?php

namespace Tiyn\MerchantApiSdk\Model\Property\SuccessUrl;

use Symfony\Component\Validator\Constraints as Assert;

trait SuccessUrlTrait
{
    #[Assert\Url]
    protected string $successUrl;
}