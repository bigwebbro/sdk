<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Symfony\Component\Validator\Constraints as Assert;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountDenormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountNormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Configuration\Validation\DeliveryMethodConstraint as AssertDeliveryMethod;
use Tiyn\MerchantApiSdk\Configuration\Validation\CurrencyConstraint as AssertCurrency;
use Tiyn\MerchantApiSdk\Configuration\Validation\ExpirationDateConstraint as AssertExpirationDate;

abstract class AbstractInvoice implements AmountNormalizerAwareInterface, AmountDenormalizerAwareInterface
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
    protected string $customerPhone;

    #[Assert\Email]
    protected string $customerEmail;

    /**
     * @var array<string, mixed>
     */
    protected array $customData;

    #[Assert\Url]
    protected string $successUrl;

    #[Assert\Url]
    protected string $failUrl;

    #[AssertDeliveryMethod]
    protected string $deliveryMethod;

    #[AssertExpirationDate]
    protected \DateTimeImmutable $expirationDate;

    /**
     * @var null|array<string, mixed>
     */
    protected ?array $ofdData = null;

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCustomerPhone(): string
    {
        return $this->customerPhone;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * @return array<string,mixed>
     */
    public function getCustomData(): array
    {
        return $this->customData;
    }

    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    public function getFailUrl(): string
    {
        return $this->failUrl;
    }

    public function getDeliveryMethod(): string
    {
        return $this->deliveryMethod;
    }

    public function getExpirationDate(): \DateTimeImmutable
    {
        return $this->expirationDate;
    }

    /**
     * @return array<string,mixed>
     */
    public function getOfdData(): ?array
    {
        return $this->ofdData;
    }

    public function setExternalId(string $externalId): self
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

    public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @param array<string,mixed> $customData
     * @return $this
     */
    public function setCustomData(array $customData): self
    {
        $this->customData = $customData;
        return $this;
    }

    public function setSuccessUrl(string $successUrl): self
    {
        $this->successUrl = trim($successUrl);
        return $this;
    }

    public function setFailUrl(string $failUrl): self
    {
        $this->failUrl = trim($failUrl);
        return $this;
    }

    public function setDeliveryMethod(string $deliveryMethod): self
    {
        $this->deliveryMethod = $deliveryMethod;
        return $this;
    }

    public function setExpirationDate(\DateTimeImmutable|string $expirationDate): self
    {
        $this->expirationDate = is_string($expirationDate) ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', $expirationDate) : $expirationDate;
        return $this;
    }

    /**
     * @param null|array<string,mixed> $ofdData
     * @return $this
     */
    public function setOfdData(?array $ofdData): self
    {
        $this->ofdData = $ofdData;
        return $this;
    }
}
