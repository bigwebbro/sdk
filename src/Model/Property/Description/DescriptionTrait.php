<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Description;

use Symfony\Component\Validator\Constraints as Assert;

trait DescriptionTrait
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 1000)]
    protected string $description;
}
