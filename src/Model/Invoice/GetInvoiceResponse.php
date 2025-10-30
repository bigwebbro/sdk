<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSk\Model\Invoice\Payment\Payment;

final class GetInvoiceResponse extends AbstractInvoice
{
    private string $uuid;

    private string $finalAmount;

    /**
     * @var Payment[]
     */
    private array $payments;

    /**
     * @var Status
     */
    private Status $status;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getFinalAmount(): string
    {
        return $this->finalAmount;
    }

    public function setFinalAmount(string $finalAmount): void
    {
        $this->finalAmount = $finalAmount;
    }

    public function getPayments(): array
    {
        return $this->payments;
    }

    /**
     * @param Payment[] $payments
     * @return void
     */
    public function setPayments(array $payments): void
    {
        $this->payments = $payments;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return void
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}