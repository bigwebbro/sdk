<?php

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

use Tiyn\MerchantApiSdk\Configuration\Validation\DeliveryMethodConstraint as AssertDeliveryMethod;

trait DeliveryMethodTrait
{
    #[AssertDeliveryMethod]
    protected string $deliveryMethod;
}