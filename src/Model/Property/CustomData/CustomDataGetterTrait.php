<?php

namespace Tiyn\MerchantApiSdk\Model\Property\CustomData;

/**
 * @property array<string, mixed> $customData
 */
trait CustomDataGetterTrait
{
    /**
     * @return array<string, mixed>
     */
    public function getCustomData(): array
    {
        return $this->customData;
    }
}
