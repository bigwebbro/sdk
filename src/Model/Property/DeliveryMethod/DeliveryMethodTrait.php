<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

use Tiyn\MerchantApiSdk\Configuration\Validation\DeliveryMethodConstraint as AssertDeliveryMethod;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

trait DeliveryMethodTrait
{
    #[AssertDeliveryMethod]
    private DeliveryMethodEnum $deliveryMethod;
}
