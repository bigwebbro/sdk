<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Description;

/**
 * @property string $description
 */
trait DescriptionSetterTrait
{
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
