<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod;

use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

/**
 * @property DeliveryMethodEnum $deliveryMethod
 */
trait DeliveryMethodGetterTrait
{
    public function getDeliveryMethod(): DeliveryMethodEnum
    {
        return $this->deliveryMethod;
    }
}
