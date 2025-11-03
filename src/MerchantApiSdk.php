<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Tiyn\MerchantApiSdk\Service\CallbackServiceInterface;
use Tiyn\MerchantApiSdk\Service\InvoicesService;

final class MerchantApiSdk implements MerchantApiSdkInterface
{
    public function __construct(
        private readonly InvoicesService $invoiceService,
        private readonly CallbackServiceInterface $callbackService,
    ) {
    }

    public function invoice(): InvoicesService
    {
        return $this->invoiceService;
    }

    public function callback(): CallbackServiceInterface
    {
        return $this->callbackService;
    }
}
