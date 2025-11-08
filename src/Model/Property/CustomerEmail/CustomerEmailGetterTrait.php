<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerEmail;

/**
 * @property null|string $customerEmail
 */
trait CustomerEmailGetterTrait
{
    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }
}
