<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ExpirationDateConstraint extends Constraint
{
    public string $message = 'The expiration date should be in the future';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
