<?php

namespace Tiyn\MerchantApiSdk\Model\Property\ExpirationDate;

/**
 * @property \DateTimeImmutable $expirationDate
 */
trait ExpirationDateGetterTrait
{
    public function getExpirationDate(): \DateTimeImmutable
    {
        return $this->expirationDate;
    }
}
