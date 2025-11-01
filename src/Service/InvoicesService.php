<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceResponse;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandlerInterface;

class InvoicesService
{
    public const INVOICE_ENDPOINT = '/invoices';

    public const INVOICE_REFUND_ENDPOINT = self::INVOICE_ENDPOINT.'/%s/refund';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly DenormalizerInterface $denormalizer,
        private readonly RequestHandlerInterface $requestHandler,
        private readonly ResponseHandlerInterface $responseHandler,
        private readonly string $secretPhrase,
    ) {
    }

    public function createInvoice(CreateInvoiceRequest $command): CreateInvoiceResponse
    {
        $json = $this->requestHandler->handleRequest($command);

        $request = (new RequestBuilder())
            ->withMethod('POST')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withBody($json)
            ->withEndpoint(self::INVOICE_ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        // TODO перенести в ResponseHandler
        try {
            $invoiceData = $this->denormalizer->denormalize($result, CreateInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }

    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse
    {
        $request = (new RequestBuilder())
            ->withMethod('GET')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withEndpoint(\sprintf('%s/%s', self::INVOICE_ENDPOINT, $command->getUuid()))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        // TODO перенести в ResponseHandler
        try {
            $invoice = $this->denormalizer->denormalize($result, GetInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoice;
    }

    public function makeRefundByInvoice(string $invoiceUuid, CreateRefundRequest $command): CreateRefundResponse
    {
        $json = $this->requestHandler->handleRequest($command);

        $request = (new RequestBuilder())
            ->withMethod('PUT')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withBody($json)
            ->withEndpoint(\sprintf(self::INVOICE_REFUND_ENDPOINT, $invoiceUuid))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        try {
            $refundData = $this->denormalizer->denormalize($result, CreateRefundResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $refundData;
    }
}
