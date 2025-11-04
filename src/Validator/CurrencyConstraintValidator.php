<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\CurrencyEnum;

final class CurrencyConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CurrencyConstraint) {
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

        if ($value !== CurrencyEnum::KZT->value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
