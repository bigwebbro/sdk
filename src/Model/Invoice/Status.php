<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class Status
{
    private  string $message;

    private  string $name;

    private  \DateTimeImmutable $time;

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