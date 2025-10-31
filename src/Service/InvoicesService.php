<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use JMS\Serializer\ArrayTransformerInterface;
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
            ->withEndpoint(sprintf("%s/%s",self::ENDPOINT, $command->getUuid()))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        return GetInvoiceResponse::fromArray($result);
    }
}
