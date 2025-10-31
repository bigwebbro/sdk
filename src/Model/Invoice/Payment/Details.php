<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Details
{
    private string $account;

    private string $paymentToken;

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): Details
    {
        $this->account = $account;
        return $this;
    }

    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }

    public function setPaymentToken(string $paymentToken): Details
    {
        $this->paymentToken = $paymentToken;
        return $this;
    }
}