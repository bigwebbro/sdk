<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class CreatedInvoiceResponse
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $externalId,
        private readonly string $paymentLink
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getPaymentLink(): string
    {
        return trim($this->paymentLink);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            uuid: $data['uuid'],
            externalId: $data['externalId'],
            paymentLink: $data['paymentLink']
        );
    }
}
