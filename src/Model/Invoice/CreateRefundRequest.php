<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountNormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Model\Property\Amount\AmountSetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Amount\AmountTrait;
use Tiyn\MerchantApiSdk\Model\Property\Reason\ReasonSetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Reason\ReasonTrait;
use Tiyn\MerchantApiSdk\Model\Property\RequestId\RequestIdSetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\RequestId\RequestIdTrait;
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
