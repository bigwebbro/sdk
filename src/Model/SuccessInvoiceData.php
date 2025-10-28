<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model;

final class SuccessInvoiceData
{
    public function __construct(
        private string $uuid,
        private string $externalId,
        private string $paymentLink
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
        return trim($this->paymentLink); // убираем возможные пробелы в конце
    }
}
