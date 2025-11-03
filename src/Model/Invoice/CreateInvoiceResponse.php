<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\ExternalId\ExternalIdGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\ExternalId\ExternalIdTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentLink\PaymentLinkGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentLink\PaymentLinkTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidTrait;

final class CreateInvoiceResponse
{
    use UuidTrait;
    use UuidGetterTrait;

    use ExternalIdTrait;
    use ExternalIdGetterTrait;

    use PaymentLinkTrait;
    use PaymentLinkGetterTrait;
}
