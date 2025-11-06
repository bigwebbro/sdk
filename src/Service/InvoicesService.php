<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Refund\CreateRefundResponse;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandlerInterface;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;

final class InvoicesService extends AbstractRequestService implements InvoicesServiceInterface
{
    public const INVOICE_ENDPOINT = 'invoices';
    public const INVOICE_GET_ENDPOINT = self::INVOICE_ENDPOINT . '/%s';
    public const INVOICE_REFUND_ENDPOINT = self::INVOICE_ENDPOINT . '/%s/refund';

    public function __construct(
        private readonly ClientInterface          $client,
        private readonly DenormalizerInterface    $denormalizer,
        private readonly RequestHandlerInterface  $requestHandler,
        private readonly ResponseHandlerInterface $responseHandler,
        string                                    $secretPhrase,
        string                                    $apiKey,
    ) {
        parent::__construct($secretPhrase, $apiKey);
    }

    public function createInvoice(CreateInvoiceRequest $command): CreateInvoiceResponse
    {
        $json = $this->requestHandler->handleRequest($command);
        $request = (new RequestBuilder())
            ->withMethod('POST')
            ->withBody($json)
            ->withEndpoint(self::INVOICE_ENDPOINT)
            ->withApiKey($this->apiKey)
            ->buildWithSign($this->secretPhrase);

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        try {
            $invoiceData = $this->denormalizer->denormalize($result, CreateInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new DataTransformationException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }

    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse
    {
        $request = (new RequestBuilder())
            ->withMethod('GET')
            ->withApiKey($this->apiKey)
            ->withEndpoint($this->getEndpoint(self::INVOICE_GET_ENDPOINT, $command->getUuid()))
            ->build();

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        try {
            $invoice = $this->denormalizer->denormalize($result, GetInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new DataTransformationException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoice;
    }

    public function makeRefundByInvoice(string $invoiceUuid, CreateRefundRequest $command): CreateRefundResponse
    {
        $json = $this->requestHandler->handleRequest($command);

        $request = (new RequestBuilder())
            ->withMethod('PUT')
            ->withBody($json)
            ->withEndpoint($this->getEndpoint(self::INVOICE_REFUND_ENDPOINT, $invoiceUuid))
            ->withApiKey($this->apiKey)
            ->buildWithSign($this->secretPhrase);

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        try {
            $refundData = $this->denormalizer->denormalize($result, CreateRefundResponse::class);
        } catch (ExceptionInterface $e) {
            throw new DataTransformationException($e->getMessage(), $e->getCode(), $e);
        }

        return $refundData;
    }
}
