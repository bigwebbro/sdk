<?php

namespace Tiyn\MerchantApiSdk\Model\Property\OfdData;

/**
 * @property null|array<string, mixed> $ofdData
 */
trait OfdDataGetterTrait
{
    /**
     * @return null|array<string, mixed>
     */
    public function getOfdData(): ?array
    {
        return $this->ofdData;
    }
}
