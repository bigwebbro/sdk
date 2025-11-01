<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Email;

/**
 * @property string $customerEmail
 */
trait CustomerEmailGetterTrait
{
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }
}
