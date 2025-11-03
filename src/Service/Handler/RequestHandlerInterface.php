<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Tiyn\MerchantApiSdk\Model\RequestModelInterface;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\ValidationException;

interface RequestHandlerInterface
{
    /**
     * @throws ValidationException
     */
    public function handleRequest(RequestModelInterface $response): string;
}
