<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;

interface RequestHandlerInterface
{
    /**
     * @throws ValidationException
     */
    public function handleRequest(RequestModelInterface $response): string;
}
