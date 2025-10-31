<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Status
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public static function fromArray(array $data): self
    {
        $status = new self();
        $status->name = $data['name'];

        return $status;
    }
}