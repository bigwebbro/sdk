<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use JMS\Serializer\SerializerInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedInvoiceResponse;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedRefundResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\Status;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandlerInterface;

class InvoicesService
{
    public const INVOICE_ENDPOINT = '/invoices';

    public const INVOICE_REFUND_ENDPOINT = self::INVOICE_ENDPOINT.'/%s/refund';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly DenormalizerInterface $denormalizer,
        private readonly \Symfony\Component\Serializer\SerializerInterface $serializer,
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
            ->withEndpoint(self::INVOICE_ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        var_dump($json);

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
        $request = (new RequestBuilder())
            ->withMethod('GET')
            ->withHeaders(['Content-Type' => 'application/json']) // TODO вынести в конфиг клиента
            ->withEndpoint(sprintf('%s/%s', self::INVOICE_ENDPOINT, $command->getUuid()))
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $result = $this->responseHandler->handleResponse($response);

        // TODO перенести в ResponseHandler
        try {
            /**
             * @var GetInvoiceResponse $invoiceData
             */
            $invoiceData = $this->denormalizer->denormalize($result, GetInvoiceResponse::class, context: [
                AbstractNormalizer::CALLBACKS => [
                    'expirationDate' => fn($v) => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', $v),
                    'status' => fn($v) => $this->serializer->denormalize($v, Status::class, 'json')
                ],
            ]);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }

    public function createRefund(string $invoiceUuid, CreateRefundRequest $command): CreatedRefundResponse
    {
        $json = $this->requestHandler->handleRequest($command);
        var_dump($json);

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
            /**
             * @var CreatedRefundResponse $invoiceData
             */
            $refundData = $this->denormalizer->denormalize($result, CreatedRefundResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $refundData;
    }
}
