<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

use Tiyn\MerchantApiSdk\Configuration\Validation\DeliveryMethodConstraint as AssertDeliveryMethod;

trait DeliveryMethodTrait
{
    #[AssertDeliveryMethod]
    private string $deliveryMethod;
}
