<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Payment
{
    private string $paymentMethod;
    private ?Details $details = null;
    private Status $status;

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getDetails(): ?Details
    {
        return $this->details;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $payment = new self();
        $payment->paymentMethod = $data['paymentMethod'];
        if (isset($data['details'])) {
            $payment->details = Details::fromArray($data['details']);
        }
        $payment->status = Status::fromArray($data['status']);

        return $payment;
    }
}
