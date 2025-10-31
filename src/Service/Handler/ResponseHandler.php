<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Exception\Validation\EmptyDataException;

class ResponseHandler implements ResponseHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        try {
            $result = json_decode($body, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new JsonProcessingException($e->getMessage(), $statusCode, $e);
        }

        if (isset($result['error'])) {
            $error = Error::fromArray($result['error']);
            throw match ($statusCode) {
                400 => new EntityErrorException($error, $statusCode),
                401 => new ApiKeyException($error, $statusCode),
                403 => new SignException($error, $statusCode),
                default => new ApiMerchantErrorException($error, $statusCode),
            };
        }

        if (isset($result['data'])) {
            return $result['data'];
        }

        if (!empty($result)) {
            return $result;
        }

        throw new EmptyDataException("Unexpected error", $response->getStatusCode());
    }
}
