<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;

final class Status
{
    private string $message;

    private string $name;

    private \DateTimeImmutable $time;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        // TODO вынести
        $time = null;
        if (isset($data['time'])) {
            $time = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', $data['time']);
            if (false === $time) {
                throw new WrongDataException('Invalid expirationDate format');
            }
        }

        $status = new self();
        $status->message = $data['message'];
        $status->name = $data['name'];
        $status->time = $time;
        return $status;
    }

}
