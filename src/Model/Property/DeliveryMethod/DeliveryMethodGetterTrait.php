<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

/**
 * @property string $deliveryMethod
 */
trait DeliveryMethodGetterTrait
{
    public function getDeliveryMethod(): string
    {
        return $this->deliveryMethod;
    }
}
