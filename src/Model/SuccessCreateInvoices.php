<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model;

final class SuccessCreateInvoices
{
    public function __construct(
        private bool        $success,
        private SuccessInvoiceData $data
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): SuccessInvoiceData
    {
        return $this->data;
    }
}
