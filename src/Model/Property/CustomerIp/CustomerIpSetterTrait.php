<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerIp;

/**
 * @property string $customerIp
 */
trait CustomerIpSetterTrait
{
    public function setCustomerIp(string $customerIp): static
    {
        $this->customerIp = $customerIp;

        return $this;
    }
}
