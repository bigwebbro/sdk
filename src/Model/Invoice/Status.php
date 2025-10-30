<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class Status
{
    public function __construct(
        private readonly string $message,
        private readonly string $name,
        private readonly \DateTimeImmutable $time,
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }
}