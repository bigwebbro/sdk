<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Validation;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

final class ExpirationDateConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExpirationDateConstraint) {
            return;
        }

        if (null !== $value && $value <= new \DateTimeImmutable()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
