<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\UnauthorizedException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ForbiddenException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;

interface ResponseHandlerInterface
{
    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     *
     * @throws EntityErrorException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws ApiMerchantErrorException
     * @throws DataTransformationException
     */
    public function handleResponse(ResponseInterface $response): array;
}
