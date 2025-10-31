<?php

namespace Tiyn\MerchantApiSk\Model\Invoice\Payment;

final class Status
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}