<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Sign\SignException;

interface CallbackServiceInterface
{
    /**
     * @throws SignException
     * @throws DataTransformationException
     */
    public function handleInvoiceCallback(string $xSign, string $body): GetInvoiceResponse;
}
