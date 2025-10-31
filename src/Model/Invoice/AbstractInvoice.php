<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Configuration\Validation\CurrencyConstraint as AssertCurrency;
use Tiyn\MerchantApiSdk\Configuration\Validation\DeliveryMethodConstraint as AssertDeliveryMethod;
use Tiyn\MerchantApiSdk\Configuration\Validation\ExpirationDateConstraint as AssertExpirationDate;

abstract class AbstractInvoice
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    protected string $externalId;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d{1,15}(\.\d{1,2})?$/',
        message: 'Сумма должна быть числом с не более чем 15 цифрами до точки и 1–2 после.'
    )]
    #[Assert\GreaterThanOrEqual(value: 0.01, message: 'Минимальная сумма — 0.01')]
    #[Assert\LessThanOrEqual(value: 999999999999999.99, message: 'Максимальная сумма — 999 999 999 999 999.99')]
    protected string $amount;

    #[Assert\NotBlank]
    #[AssertCurrency]
    protected string $currency;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 1000)]
    protected string $description;

    #[Assert\Regex(
        pattern: '/^\+7\d{10}$/',
        message: 'Номер телефона должен быть в формате +7xxxxxxxxxx (10 цифр после +7).'
    )]
    protected ?string $customerPhone = null;

    #[Assert\Email]
    protected ?string $customerEmail = null;

    /**
     * @var array<string, mixed>
     */
    protected ?array $customData = null;

    #[Assert\Url]
    protected ?string $successUrl = null;

    #[Assert\Url]
    protected ?string $failUrl = null;

    #[AssertDeliveryMethod]
    protected ?string $deliveryMethod = null;

    #[AssertExpirationDate]
    protected ?\DateTimeImmutable $expirationDate = null;

    /**
     * @var null|array<string, mixed>
     */
    protected ?array $ofdData = null;

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setCustomerPhone(?string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    public function setCustomerEmail(?string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @param null|array<string,mixed> $customData
     */
    public function setCustomData(?array $customData): self
    {
        $this->customData = $customData;
        return $this;
    }

    public function setSuccessUrl(?string $successUrl): self
    {
        $this->successUrl = null !== $successUrl ? trim($successUrl) : null;
        return $this;
    }

    public function setFailUrl(?string $failUrl): self
    {
        $this->failUrl = null !== $failUrl ? trim($failUrl) : null;
        return $this;
    }

    public function setDeliveryMethod(string $deliveryMethod): self
    {
        $this->deliveryMethod = $deliveryMethod;
        return $this;
    }

    public function setExpirationDate(\DateTimeImmutable $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    /**
     * @param null|array<string,mixed> $ofdData
     */
    public function setOfdData(?array $ofdData): self
    {
        $this->ofdData = $ofdData;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'externalId' => $this->externalId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'deliveryMethod' => $this->deliveryMethod,
        ];

        if (null !== $this->deliveryMethod) {
            $result['deliveryMethod'] = $this->deliveryMethod;
        }

        if (null !== $this->expirationDate) {
            $result['expirationDate'] = $this->expirationDate->format('Y-m-d H:i:s.uP');
        }

        if (null !== $this->customData) {
            $result['customData'] = $this->customData;
        }

        if (null !== $this->customerPhone) {
            $result['customerPhone'] = $this->customerPhone;
        }
        if (null !== $this->customerEmail) {
            $result['customerEmail'] = $this->customerEmail;
        }
        if (null !== $this->successUrl) {
            $result['successUrl'] = $this->successUrl;
        }
        if (null !== $this->failUrl) {
            $result['failUrl'] = $this->failUrl;
        }
        if (null !== $this->ofdData) {
            $result['ofdData'] = $this->ofdData;
        }

        return $result;
    }
}
