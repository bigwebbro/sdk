<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\EmptyDataException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\WrongDataException;

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
     * @throws WrongDataException
     */
    public function handleResponse(ResponseInterface $response): array;
}
