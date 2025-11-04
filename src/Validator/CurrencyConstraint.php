<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CurrencyConstraint extends Constraint
{
    public string $message = 'Invalid currency "{{ value }}". Allowed: KZT.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
