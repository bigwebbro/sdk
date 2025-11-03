<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FinalAmount;

/**
 * @property string $finalAmount
 */
trait FinalAmountSetterTrait
{
    public function setFinalAmount(string $finalAmount): static
    {
        $this->finalAmount = $finalAmount;

        return $this;
    }
}
