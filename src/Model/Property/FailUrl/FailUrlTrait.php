<?php

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

use Symfony\Component\Validator\Constraints as Assert;

trait FailUrlTrait
{
    #[Assert\Url]
    protected string $failUrl;
}