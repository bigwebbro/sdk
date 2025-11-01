<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Uuid;

/**
 * @property string $uuid
 */
trait UuidGetterTrait
{
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
