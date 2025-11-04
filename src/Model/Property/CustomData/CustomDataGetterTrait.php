<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomData;

/**
 * @property array<null|string, mixed> $customData
 */
trait CustomDataGetterTrait
{
    /**
     * @return null|array<string, mixed>
     */
    public function getCustomData(): ?array
    {
        return $this->customData;
    }
}
