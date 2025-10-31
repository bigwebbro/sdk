<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Tiyn\MerchantApiSdk\Service\InvoicesService;

final class MerchantApiSdk
{
    public function __construct(
        private readonly InvoicesService $invoiceService,
    ) {
    }

    public function invoice(): InvoicesService
    {
        return $this->invoiceService;
    }
}
