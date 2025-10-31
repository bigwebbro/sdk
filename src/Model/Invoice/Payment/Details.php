<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Details
{
    private Details $details;

    private string  $paymentMethod;

    private Status  $status;

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function setDetails(Details $details): Details
    {
        $this->details = $details;
        return $this;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): Details
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Details
    {
        $this->status = $status;
        return $this;
    }
}