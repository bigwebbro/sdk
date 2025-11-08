<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerPhone;

/**
 * @property null|string $customerPhone
 */
trait CustomerPhoneSetterTrait
{
    public function setCustomerPhone(?string $customerPhone): static
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }
}
