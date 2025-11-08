<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidConstructorTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidGetterTrait;

final class GetInvoiceRequest
{
    use UuidConstructorTrait;
    use UuidGetterTrait;
}
