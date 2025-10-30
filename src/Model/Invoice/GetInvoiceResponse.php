<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSk\Model\Invoice\Payment\Payment;

final class GetInvoiceResponse extends AbstractInvoice
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $finalAmount,
        /**
         * @var array<Payment>
         */
        private readonly array $payments,
        private readonly Status $status,
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFinalAmount(): string
    {
        return $this->finalAmount;
    }

    /**
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}