<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class GetInvoiceRequest
{
    public function __construct(
        private readonly string $uuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
