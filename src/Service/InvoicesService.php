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
use Tiyn\MerchantApiSdk\Model\Invoice\AbstractInvoice;
use Tiyn\MerchantApiSdk\Model\Invoice\CreatedInvoiceResponse;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
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

    public function createInvoice(AbstractInvoice $createInvoices): CreatedInvoiceResponse
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
             * @var CreatedInvoiceResponse $invoiceData
             */
            $invoiceData = $this->denormalizer->denormalize($data, CreatedInvoiceResponse::class);
        } catch (ExceptionInterface $e) {
            throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
        }

        return $invoiceData;
    }

    public function getInvoice(): GetInvoiceResponse
    {
        return new GetInvoiceResponse();
    }
}
