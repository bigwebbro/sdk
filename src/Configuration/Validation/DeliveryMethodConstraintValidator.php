<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Validation;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Tiyn\MerchantApiSdk\Model\Invoice\DeliveryMethodEnum;

class DeliveryMethodConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DeliveryMethodConstraint) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        if (!\in_array($value, DeliveryMethodEnum::valuesAsArray(), true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
