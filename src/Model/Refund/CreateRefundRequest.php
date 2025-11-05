<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Refund;

use Tiyn\MerchantApiSdk\Model\Property\{Amount\AmountSetterTrait,
    Amount\AmountTrait,
    Reason\ReasonSetterTrait,
    Reason\ReasonTrait,
    RequestId\RequestIdSetterTrait,
    RequestId\RequestIdTrait};
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\AmountDenormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Serializer\Normalizer\AmountAwareNormalizationInterface;

final class CreateRefundRequest implements AmountAwareNormalizationInterface, AmountDenormalizerAwareInterface, RequestModelInterface
{
    use RequestIdTrait;
    use RequestIdSetterTrait;

    use AmountTrait;
    use AmountSetterTrait;

    use ReasonTrait;
    use ReasonSetterTrait;
}
