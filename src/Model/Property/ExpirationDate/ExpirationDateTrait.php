<?php

namespace Tiyn\MerchantApiSdk\Model\Property\ExpirationDate;

use Tiyn\MerchantApiSdk\Configuration\Validation\ExpirationDateConstraint as AssertExpirationDate;

trait ExpirationDateTrait
{
    #[AssertExpirationDate]
    protected \DateTimeImmutable $expirationDate;
}