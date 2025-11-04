<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

use Tiyn\MerchantApiSdk\Model\Invoice\Status;
use Tiyn\MerchantApiSdk\Model\Property\PaymentMethod\PaymentMethodGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentMethod\PaymentMethodTrait;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\DetailsAwareDenormalizationInterface;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\StatusAwareDenormalizationInterface;

final class Payment implements DetailsAwareDenormalizationInterface, StatusAwareDenormalizationInterface
{
    use PaymentMethodTrait;
    use PaymentMethodGetterTrait;

    /**
     * @var Details
     * @phpstan-ignore-next-line
     */
    private Details $details;

    /**
     * @var Status
     * @phpstan-ignore-next-line
     */
    private Status $status;

    public function getDetails(): Details
    {
        return $this->details;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
