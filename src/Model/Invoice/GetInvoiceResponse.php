<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;

final class GetInvoiceResponse extends AbstractInvoice
{
    private string $uuid;

    private string $finalAmount;

    /**
     * @var Payment[]
     */
    private array $payments;

    /**
     * @var Status
     */
    private Status $status;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFinalAmount(): string
    {
        return $this->finalAmount;
    }

    /**
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }


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

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCustomData(): ?array
    {
        return $this->customData;
    }

    public function getSuccessUrl(): ?string
    {
        return $this->successUrl;
    }

    public function getFailUrl(): ?string
    {
        return $this->failUrl;
    }

    public function getDeliveryMethod(): ?string
    {
        return $this->deliveryMethod;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getOfdData(): ?array
    {
        return $this->ofdData;
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        // TODO вынести
        $expirationDate = null;
        if (!empty($data['expirationDate'])) {
            $expirationDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', $data['expirationDate']);
            if (false === $expirationDate) {
                throw new WrongDataException('Invalid expirationDate format');
            }
        }

        $invoice = new self();
        $invoice->externalId = $data['externalId'];
        $invoice->amount = $data['amount'];
        $invoice->finalAmount = $data['finalAmount'];
        $invoice->currency = $data['currency'];
        $invoice->description = $data['description'];
        $invoice->deliveryMethod = $data['deliveryMethod'];
        $invoice->expirationDate = $expirationDate;
        $invoice->uuid = $data['uuid'];
        $invoice->status = Status::fromArray($data['status']);

        if (isset($data['customerPhone'])) {
            $invoice->customerPhone = $data['customerPhone'];
        }
        if (isset($data['customerEmail'])) {
            $invoice->customerEmail = $data['customerEmail'];
        }
        if (isset($data['customData'])) {
            $invoice->customData = $data['customData'];
        }
        if (isset($data['successUrl'])) {
            $invoice->successUrl = $data['successUrl'];
        }
        if (isset($data['failUrl'])) {
            $invoice->failUrl = $data['failUrl'];
        }
        if (isset($data['ofdData'])) {
            $invoice->ofdData = $data['ofdData'];
        }

        $payments = [];
        foreach ($data['payments'] ?? [] as $paymentData) {
            $payments[] = Payment::fromArray($paymentData);
        }
        $invoice->payments = $payments;

        return $invoice;
    }
}
