<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FinalAmount;

/**
 * @property string $finalAmount
 */
trait FinalAmountGetterTrait
{
    public function getFinalAmount(): string
    {
        return $this->finalAmount;
    }
}
