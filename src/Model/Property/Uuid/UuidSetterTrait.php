<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Uuid;

/**
 * @property string $uuid
 */
trait UuidSetterTrait
{
    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
