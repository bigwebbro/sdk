<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

trait DeliveryMethodTrait
{
    private ?DeliveryMethodEnum $deliveryMethod = null;
}
