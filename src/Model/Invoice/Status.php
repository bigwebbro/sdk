<?php

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Configuration\Normalizer\InvoiceStatusInterface;
use Tiyn\MerchantApiSdk\Model\Property\Message\MessageGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Message\MessageTrait;
use Tiyn\MerchantApiSdk\Model\Property\Name\NameGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Name\NameTrait;
use Tiyn\MerchantApiSdk\Model\Property\Time\TimeGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Time\TimeTrait;

final class Status implements InvoiceStatusInterface
{
    use NameTrait;
    use NameGetterTrait;
    use MessageTrait;
    use MessageGetterTrait;
    use TimeTrait;
    use TimeGetterTrait;
}