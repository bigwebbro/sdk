<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Tiyn\MerchantApiSdk\Client\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundResponse;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ForbiddenException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\UnauthorizedException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\ValidationException;

interface InvoicesServiceInterface
{
    /**
     * @throws ValidationException
     * @throws ConnectionException
     * @throws EntityErrorException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws ApiMerchantErrorException
     * @throws DataTransformationException
     */
    public function createInvoice(CreateInvoiceRequest $command): CreateInvoiceResponse;

    /**
     * @throws ConnectionException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws ApiMerchantErrorException
     * @throws DataTransformationException
     */
    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse;

    /**
     * @throws ValidationException
     * @throws ConnectionException
     * @throws EntityErrorException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws ApiMerchantErrorException
     * @throws DataTransformationException
     */
    public function makeRefundByInvoice(string $invoiceUuid, CreateRefundRequest $command): CreateRefundResponse;
}
