<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Email;

/**
 * @property string $customerEmail
 */
trait CustomerEmailSetterTrait
{
    public function setCustomerEmail(string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }
}
