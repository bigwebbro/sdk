<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\ToArrayInterface;

interface RequestHandlerInterface
{
    /**
     * @throws ValidationException
     * @throws JsonProcessingException
     */
    public function handleRequest(ToArrayInterface $request): string;
}
