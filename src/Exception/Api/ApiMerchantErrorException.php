<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Exception\Api;

use Tiyn\MerchantApiSdk\Model\Error;

class ApiMerchantErrorException extends \RuntimeException
{
    public function __construct(private readonly Error $error, int $code, \Throwable $previous = null)
    {
        parent::__construct($this->error->getMessage(), $code, $previous);
    }

    public function getError(): Error
    {
        return $this->error;
    }
}
