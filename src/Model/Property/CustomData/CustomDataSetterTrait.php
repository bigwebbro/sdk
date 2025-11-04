<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomData;

/**
 * @property array<null|string, mixed> $customData
 */
trait CustomDataSetterTrait
{
    /**
     * @param null|array<string, mixed> $customData
     */
    public function setCustomData(?array $customData): static
    {
        $this->customData = $customData;

        return $this;
    }
}
