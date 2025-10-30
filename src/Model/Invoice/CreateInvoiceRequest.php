<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;

final class CreateInvoiceRequest extends AbstractInvoice implements RequestModelInterface
{
    #[Assert\Ip]
    private string $customerIp;

    public function getCustomerIp(): string
    {
        return $this->customerIp;
    }

    public function setCustomerIp(string $customerIp): self
    {
        $this->customerIp = $customerIp;

        return $this;
    }
}