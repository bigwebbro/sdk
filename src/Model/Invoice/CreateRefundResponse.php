<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{
    RequestId\RequestIdGetterTrait,
    RequestId\RequestIdTrait,
    Uuid\UuidGetterTrait,
    Uuid\UuidTrait
};

final class CreateRefundResponse
{
    use UuidTrait;
    use UuidGetterTrait;

    use RequestIdTrait;
    use RequestIdGetterTrait;
}
