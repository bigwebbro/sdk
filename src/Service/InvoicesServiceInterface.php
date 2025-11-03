<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundResponse;

interface InvoicesServiceInterface
{
    public function createInvoice(CreateInvoiceRequest $command): CreateInvoiceResponse;

    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse;

    public function makeRefundByInvoice(string $invoiceUuid, CreateRefundRequest $command): CreateRefundResponse;
}
