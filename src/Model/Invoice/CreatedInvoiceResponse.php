<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class CreatedInvoiceResponse
{
    private string $uuid;

    private string $externalId;

    private string $paymentLink;

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

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $createdInvoice = new self();
        $createdInvoice->uuid = $data['uuid'];
        $createdInvoice->externalId = $data['externalId'];
        $createdInvoice->paymentLink = $data['paymentLink'];

        return $createdInvoice;
    }
}
