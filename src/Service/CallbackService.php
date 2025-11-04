<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\JsonProcessingException;

final class CallbackService implements CallbackServiceInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function handleInvoiceCallback(string $invoice): GetInvoiceResponse
    {
        try {
            $result = $this->serializer->deserialize($invoice, GetInvoiceResponse::class, 'json');
        } catch (ExceptionInterface $e) {
            throw new JsonProcessingException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }
}
