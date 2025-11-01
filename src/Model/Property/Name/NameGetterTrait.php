<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Name;

/**
 * @property string $name
 */
trait NameGetterTrait
{
    public function getName(): string
    {
        return $this->name;
    }
}
