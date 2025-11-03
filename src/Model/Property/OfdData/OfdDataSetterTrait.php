<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\OfdData;

/**
 * @property null|array<string, mixed> $ofdData
 */
trait OfdDataSetterTrait
{
    /**
     * @param null|array<string, mixed> $ofdData
     */
    public function setOfdData(?array $ofdData): static
    {
        $this->ofdData = $ofdData;

        return $this;
    }
}
