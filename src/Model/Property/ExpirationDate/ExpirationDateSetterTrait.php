<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\ExpirationDate;

/**
 * @property \DateTimeImmutable $expirationDate
 */
trait ExpirationDateSetterTrait
{
    public function setExpirationDate(\DateTimeImmutable $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
