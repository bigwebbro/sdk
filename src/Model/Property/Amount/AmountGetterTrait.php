<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Amount;

/**
 * @property string $amount
 */
trait AmountGetterTrait
{
    public function getAmount(): string
    {
        return $this->amount;
    }
}
