<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Refund;

use Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer\AmountNormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Model\Property\{
    Amount\AmountSetterTrait,
    Amount\AmountTrait,
    Reason\ReasonSetterTrait,
    Reason\ReasonTrait,
    RequestId\RequestIdSetterTrait,
    RequestId\RequestIdTrait
};
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;

final class CreateRefundRequest implements AmountNormalizerAwareInterface, RequestModelInterface
{
    use RequestIdTrait;
    use RequestIdSetterTrait;

    use AmountTrait;
    use AmountSetterTrait;

    use ReasonTrait;
    use ReasonSetterTrait;
}
