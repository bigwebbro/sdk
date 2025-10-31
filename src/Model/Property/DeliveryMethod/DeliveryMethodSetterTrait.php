<?php

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

/**
 * @property string $deliveryMethod
 */
trait DeliveryMethodSetterTrait
{
    public function setDeliveryMethod(string $deliveryMethod): static
    {
        $this->deliveryMethod = $deliveryMethod;

        return $this;
    }
}
