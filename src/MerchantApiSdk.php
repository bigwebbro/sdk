<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Tiyn\MerchantApiSdk\Service\InvoicesService;

final class MerchantApiSdk
{
    public function __construct(
        private readonly InvoicesService $invoicesService,
    ) {
    }

    public function invoices(): InvoicesService
    {
        return $this->invoicesService;
    }
}
