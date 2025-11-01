<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\RequestId;

use Symfony\Component\Validator\Constraints as Assert;

trait RequestIdTrait
{
    #[Assert\NotBlank]
    private string $requestId;
}
