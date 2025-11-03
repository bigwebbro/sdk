<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Reason;

use Symfony\Component\Validator\Constraints as Assert;

trait ReasonTrait
{
    #[Assert\NotBlank]
    private string $reason;
}
