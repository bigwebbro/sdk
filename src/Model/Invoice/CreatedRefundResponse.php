<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class CreatedRefundResponse
{
    private string $uuid;

    private string $requestId;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }
}