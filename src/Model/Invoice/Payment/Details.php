<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Payment;

use Tiyn\MerchantApiSdk\Model\Property\Account\AccountGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Account\AccountTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentToken\PaymentTokenGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\PaymentToken\PaymentTokenTrait;

final class Details
{
    use AccountTrait;
    use AccountGetterTrait;
    use PaymentTokenTrait;
    use PaymentTokenGetterTrait;
}
