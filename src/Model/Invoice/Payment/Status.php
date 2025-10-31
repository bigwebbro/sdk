<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

final class Status
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $status = new self();
        $status->name = $data['name'];

        return $status;
    }
}
