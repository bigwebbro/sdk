<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Handler;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\Invoices\CreateInvoices;
use Tiyn\MerchantApiSdk\Model\Invoices\InvoicesData;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;

class InvoicesHandler
{
    public const ENDPOINT = '/invoices';

    public function __construct(
        private ClientInterface $client,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private DenormalizerInterface $denormalizer,
        private ResponseHandlerInterface $responseHandler,
        private string $secretPhrase,
    ) {
    }

    public function createInvoices(CreateInvoices $createInvoices): InvoicesData
    {
        $violations = $this->validator->validate($createInvoices);
        if ($violations->count() > 0) {
            throw new ValidationException('Invalid data');
        }

        $json = $this->serializer->serialize($createInvoices, 'json');

        $request = (new RequestBuilder())
            ->withMethod('POST')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody($json)
            ->withEndpoint(self::ENDPOINT)
            ->buildWithSign($this->secretPhrase)
        ;

        $response = $this->client->sendRequest($request);
        $data = $this->responseHandler->handleResponse($response);

        try {
            /**
             * @var InvoicesData $invoicesData
             */
            $invoicesData = $this->denormalizer->denormalize($data, InvoicesData::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoicesData;
    }
}
