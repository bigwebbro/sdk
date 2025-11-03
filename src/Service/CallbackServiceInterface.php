<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;

interface CallbackServiceInterface
{
    /**
     * @param string $invoice callback json body
     * @return GetInvoiceResponse
     */
    public function handleInvoiceCallback(string $invoice): GetInvoiceResponse;
}
