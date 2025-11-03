<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model;

final class Error
{
    public function __construct(
        private readonly string $code,
        private readonly string $message,
        private readonly string $correlationId
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}
