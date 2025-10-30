<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DeliveryMethodConstraint extends Constraint
{
    public string $message = 'Invalid delivery method "{{ value }}". Allowed: EMAIL, SMS, URL.';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
