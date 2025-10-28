<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Tiyn\MerchantApiSdk\Client\Http\Guzzle\GuzzleClient;
use Tiyn\MerchantApiSdk\Handler\InvoicesHandler;

final class MerchantApiSdk
{
    public function __construct(
        private InvoicesHandler $invoicesHandler,
    ) {
    }

    public function invoices(): InvoicesHandler
    {
        return $this->invoicesHandler;
    }
}
