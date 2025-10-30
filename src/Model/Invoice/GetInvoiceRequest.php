<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\RequestModelInterface;

final class GetInvoiceRequest implements RequestModelInterface
{
    public function __construct(
        private readonly string $uuid,
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }
}