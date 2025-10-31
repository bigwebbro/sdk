<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

final class CreatedRefundResponse
{
    /**
     * @var string
     */
    private string $uuid;

    /**
     * @var string
     */
    private string $requestId;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $instance = new self();
        $instance->uuid = $data['uuid'] ?? '';
        $instance->requestId = $data['requestId'] ?? '';

        return $instance;
    }
}
