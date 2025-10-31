<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Details
{
    private ?string $account;

    private ?string $paymentToken;

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function getPaymentToken(): ?string
    {
        return $this->paymentToken;
    }

    public static function fromArray(array $data): self
    {
        $details = new self();
        if (isset($data['account'])) {
            $details->account = $data['account'];
        }
        if (isset($data['paymentToken'])) {
            $details->paymentToken = $data['paymentToken'];
        }

        return $details;
    }
}