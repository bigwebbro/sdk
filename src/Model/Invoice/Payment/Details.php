<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Details
{
    private ?string $account = null;

    private ?string $paymentToken = null;

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function getPaymentToken(): ?string
    {
        return $this->paymentToken;
    }

    /**
     * @param array<string, string> $data
     * @return self
     */
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
