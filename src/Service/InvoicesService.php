<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestBuilder;
use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\InvoiceData;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandlerInterface;

class InvoicesService
{
    public const ENDPOINT = '/invoices';

    public function __construct(
        private readonly ClientInterface $client,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly DenormalizerInterface $denormalizer,
        private readonly ResponseHandlerInterface $responseHandler,
        private readonly string $secretPhrase,
    ) {
    }

    public function createInvoice(CreateInvoiceRequest $createInvoices): InvoiceData
    {
        $violations = $this->validator->validate($createInvoices);
        if ($violations->count() > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage();
            }
            throw new ValidationException(implode(', ', $messages));
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
             * @var InvoiceData $invoiceData
             */
            $invoiceData = $this->denormalizer->denormalize($data, InvoiceData::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }
}
