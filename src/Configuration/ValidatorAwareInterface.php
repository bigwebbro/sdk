<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration;

use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ValidatorAwareInterface
{
    public function setValidator(ValidatorInterface $validator): void;
}
