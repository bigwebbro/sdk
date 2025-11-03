<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;

final class CallbackService implements CallbackServiceInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function handleInvoiceCallback(string $invoice): GetInvoiceResponse
    {
        return $this->serializer->deserialize($invoice, GetInvoiceResponse::class, 'json');
    }
}
