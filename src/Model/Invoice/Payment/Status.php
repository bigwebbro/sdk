<?php

namespace Tiyn\MerchantApiSk\Model\Invoice\Payment;

final class Status
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}