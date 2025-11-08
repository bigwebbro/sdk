<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerEmail;

/**
 * @property null|string $customerEmail
 */
trait CustomerEmailSetterTrait
{
    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }
}
