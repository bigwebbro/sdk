<?php

namespace Tiyn\MerchantApiSk\Model\Invoice\Payment;

final class Payment
{
    public function __construct(
        private readonly string $paymentMethod,
        private readonly Details $details,
        private readonly Status $status,
    ) {
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}