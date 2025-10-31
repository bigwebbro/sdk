<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Exception\Validation\EmptyDataException;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;

interface ResponseHandlerInterface
{
    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     *
     * @throws EntityErrorException
     * @throws ApiKeyException
     * @throws SignException
     * @throws ApiMerchantErrorException
     * @throws JsonProcessingException
     * @throws EmptyDataException
     */
    public function handleResponse(ResponseInterface $response): array;
}
