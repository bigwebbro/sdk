<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{
    ExternalId\ExternalIdGetterTrait,
    ExternalId\ExternalIdTrait,
    PaymentLink\PaymentLinkGetterTrait,
    PaymentLink\PaymentLinkTrait,
    Uuid\UuidGetterTrait,
    Uuid\UuidTrait
};

final class CreateInvoiceResponse
{
    use UuidTrait;
    use UuidGetterTrait;

    use ExternalIdTrait;
    use ExternalIdGetterTrait;

    use PaymentLinkTrait;
    use PaymentLinkGetterTrait;
}
