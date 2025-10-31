<?php

namespace Tiyn\MerchantApiSdk\Model\Property\ExternalId;

use Symfony\Component\Validator\Constraints as Assert;

trait
ExternalIdTrait
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    protected string $externalId;
}