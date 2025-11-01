<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Description;

/**
 * @property string $description
 */
trait DescriptionGetterTrait
{
    public function getDescription(): string
    {
        return $this->description;
    }
}
