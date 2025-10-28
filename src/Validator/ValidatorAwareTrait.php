<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorAwareTrait
{
    protected ?ValidatorInterface $validator = null;

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}
