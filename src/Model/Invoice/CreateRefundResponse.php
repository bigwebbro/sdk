<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\RequestId\RequestIdGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\RequestId\RequestIdTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidTrait;

final class CreateRefundResponse
{
    use UuidTrait;
    use UuidGetterTrait;

    use RequestIdTrait;
    use RequestIdGetterTrait;
}
