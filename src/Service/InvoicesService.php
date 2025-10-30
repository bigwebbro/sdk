<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedInvoiceResponse;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandlerInterface;

class InvoicesService
{
    public const ENDPOINT = '/invoices';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly DenormalizerInterface $denormalizer,
        private readonly RequestHandlerInterface $requestHandler,
        private readonly ResponseHandlerInterface $responseHandler,
        private readonly string $secretPhrase,
    ) {
    }

    public function createInvoice(CreateInvoiceRequest $command): CreatedInvoiceResponse
    {
        $json = $this->requestHandler->handleRequest($command);

        $request = (new RequestBuilder())
            ->withMethod('POST')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withBody($json)
            ->withEndpoint(self::ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        // TODO перенести в ResponseHandler
        try {
            /**
             * @var CreatedInvoiceResponse $invoiceData
             */
            $invoiceData = $this->denormalizer->denormalize($result, CreatedInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }

    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse
    {
        $json = $this->requestHandler->handleRequest($command);
        $request = (new RequestBuilder())
            ->withMethod('GET')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withBody($json)
            ->withEndpoint(self::ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        // TODO перенести в ResponseHandler
        try {
            /**
             * @var GetInvoiceResponse $invoiceData
             */
            $invoiceData = $this->denormalizer->denormalize($result, GetInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }
}
