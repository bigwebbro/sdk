<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Model\ToArrayInterface;

final class CreateRefundRequest implements ToArrayInterface
{
    #[Assert\NotBlank]
    private string $requestId;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d{1,15}(\.\d{1,2})?$/',
        message: 'Сумма должна быть числом с не более чем 15 цифрами до точки и 1–2 после.'
    )]
    #[Assert\GreaterThanOrEqual(value: 0.01, message: 'Минимальная сумма — 0.01')]
    #[Assert\LessThanOrEqual(value: 999999999999999.99, message: 'Максимальная сумма — 999 999 999 999 999.99')]
    private string $amount;

    #[Assert\NotBlank]
    private string $reason;

    public function setRequestId(string $requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'requestId' => $this->requestId,
            'amount' => $this->amount,
            'reason' => $this->reason,
        ];
    }
}
