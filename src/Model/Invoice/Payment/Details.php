<?php

namespace Tiyn\MerchantApiSk\Model\Invoice\Payment;

final class Details
{
    public function __construct(
        private readonly Details $details,
        private readonly string  $paymentMethod,
        private readonly Status  $status,
    ) {
    }

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}