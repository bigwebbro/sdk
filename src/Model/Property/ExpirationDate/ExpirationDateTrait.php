<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\ExpirationDate;

use Tiyn\MerchantApiSdk\Configuration\Validation\ExpirationDateConstraint as AssertExpirationDate;

trait ExpirationDateTrait
{
    #[AssertExpirationDate]
    private \DateTimeImmutable $expirationDate;
}
