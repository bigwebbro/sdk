<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Payment
{
    private string $paymentMethod;
    private Details $details;
    private Status $status;

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function setDetails(Details $details): void
    {
        $this->details = $details;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}