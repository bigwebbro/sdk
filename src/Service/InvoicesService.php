<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Psr\Http\Client\ClientInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedRefundResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandlerInterface;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;

class InvoicesService
{
    public const ENDPOINT = '/invoices';

    public const REFUND_SLUG = '/refund';

    public function __construct(
        private readonly ClientInterface $client,
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

        return CreatedInvoiceResponse::fromArray($result);
    }

    public function getInvoice(GetInvoiceRequest $command): GetInvoiceResponse
    {
        $request = (new RequestBuilder())
            ->withMethod('GET')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withEndpoint(\sprintf("%s/%s", self::ENDPOINT, $command->getUuid()))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        return GetInvoiceResponse::fromArray($result);
    }

    public function createRefund(string $invoiceUuid, CreateRefundRequest $command): CreatedRefundResponse
    {
        $json = $this->requestHandler->handleRequest($command);

        $request = (new RequestBuilder())
            ->withMethod('PUT')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withBody($json)
            ->withEndpoint(\sprintf('%s/%s%s', self::ENDPOINT, $invoiceUuid, self::REFUND_SLUG))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        return CreatedRefundResponse::fromArray($result);
    }
}
