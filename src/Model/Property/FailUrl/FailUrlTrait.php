<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

use Symfony\Component\Validator\Constraints as Assert;

trait FailUrlTrait
{
    #[Assert\Url]
    private string $failUrl;
}
