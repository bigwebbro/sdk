<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Handler;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Exception\HttpConnectionException;
use Tiyn\MerchantApiSdk\Exception\HttpException;
use Tiyn\MerchantApiSdk\Exception\JsonProcessingException;
use Tiyn\MerchantApiSdk\Exception\ValidationException;
use Tiyn\MerchantApiSdk\Model\CreateInvoices;
use Tiyn\MerchantApiSdk\Model\SuccessCreateInvoices;

class InvoicesHandler
{
    public const ENDPOINT = '/invoices';

    public function __construct(
        private ClientInterface $client,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private string $secretPhrase,
    ) {
    }

    public function createInvoices(CreateInvoices $createInvoices): SuccessCreateInvoices
    {
        $violations = $this->validator->validate($createInvoices);
        if ($violations->count() > 0) {
            throw new ValidationException('Invalid data');
        }

        $request = (new RequestBuilder())
            ->withMethod('POST')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody($createInvoices)
            ->withEndpoint(self::ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        try {
            $response = $this->client->sendRequest($request);
        } catch (NetworkExceptionInterface $e) {
            throw new HttpConnectionException($e->getMessage(), $e->getCode(), $e);
        }

        $statusCode = $response->getStatusCode();

        $result = match (true) {
            $statusCode >= 400 && $statusCode < 500 => new HttpException(),
            $statusCode >= 500 && $statusCode < 600 => new HttpException(),
            default => null,
        };

        if (null !== $result) {
            throw $result;
        }

        /**
         * @var SuccessCreateInvoices $successCreateInvoices
         */
        $successCreateInvoices = $this->serializer->deserialize($response->getBody()->getContents(), SuccessCreateInvoices::class, 'json');

        return $successCreateInvoices;
    }
}
