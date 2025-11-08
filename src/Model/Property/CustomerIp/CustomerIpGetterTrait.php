<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerIp;

/**
 * @property null|string $customerIp
 */
trait CustomerIpGetterTrait
{
    public function getCustomerIp(): string
    {
        return $this->customerIp;
    }
}
