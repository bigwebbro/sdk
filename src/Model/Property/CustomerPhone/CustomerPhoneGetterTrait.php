<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerPhone;

/**
 * @property null|string $customerPhone
 */
trait CustomerPhoneGetterTrait
{
    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }
}
