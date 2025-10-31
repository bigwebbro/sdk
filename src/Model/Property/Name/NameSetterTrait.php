<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Name;

/**
 * @property string $name
 */
trait NameSetterTrait
{
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
