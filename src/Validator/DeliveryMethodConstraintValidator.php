<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

final class DeliveryMethodConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DeliveryMethodConstraint) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof DeliveryMethodEnum) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
