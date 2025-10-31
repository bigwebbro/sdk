<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Model\ToArrayInterface;

final class CreateInvoiceRequest extends AbstractInvoice implements ToArrayInterface
{
    #[Assert\Ip]
    protected ?string $customerIp = null;

    public function toArray(): array
    {
        $result = parent::toArray();

        if (null !== $this->customerIp) {
            $result['customerIp'] = $this->customerIp;
        }

        return $result;
    }

    public function setCustomerIp(string $customerIp): self
    {
        $this->customerIp = $customerIp;
        return $this;
    }
}
