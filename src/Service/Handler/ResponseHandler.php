<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\BlockedRequestException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceUnavailableException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\TimeoutException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\EmptyDataException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\WrongDataException;

final class ResponseHandler implements ResponseHandlerInterface
{
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const REQUEST_TIMEOUT = 408;
    public const BLOCKED_REQUEST = 418;

    public const HANDLED_HTTP_4XX_CODES = [
        self::BAD_REQUEST,
        self::UNAUTHORIZED,
        self::FORBIDDEN,
        self::REQUEST_TIMEOUT,
        self::BLOCKED_REQUEST,
    ];

    public function __construct(
        private readonly DecoderInterface      $decoder,
        private readonly DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        if (
            !\in_array($statusCode, self::HANDLED_HTTP_4XX_CODES, true)
            && $statusCode < 500 && $statusCode >= 300
        ) {
            throw new ServiceException('Unhandled http code', $statusCode);
        }

        $serviceException = match (true) {
            $statusCode >= 500 => new ServiceUnavailableException(\sprintf('Service unavailable with status code %d', $statusCode), $statusCode),
            self::REQUEST_TIMEOUT === $statusCode => new TimeoutException(\sprintf('%d Request Timeout', $statusCode), $statusCode),
            self::BLOCKED_REQUEST === $statusCode => new BlockedRequestException(\sprintf('%d Request was blocked', $statusCode), $statusCode),
            default => null
        };

        if (null !== $serviceException) {
            throw $serviceException;
        }

        $body = (string)$response->getBody();

        try {
            $array = $this->decoder->decode($body, 'json', ['json_decode_associative' => true]);
        } catch (ExceptionInterface $e) {
            throw new JsonProcessingException($e->getMessage(), $e->getCode(), $e);
        }

        if (isset($array['error'])) {
            try {
                $error = $this->denormalizer->denormalize($array['error'], Error::class, 'json');
            } catch (ExceptionInterface $e) {
                throw new WrongDataException($e->getMessage(), $e->getCode(), $e);
            }

            throw match ($statusCode) {
                self::BAD_REQUEST => new EntityErrorException($error, $statusCode),
                self::UNAUTHORIZED => new ApiKeyException($error, $statusCode),
                self::FORBIDDEN => new SignException($error, $statusCode),
                default => new ApiMerchantErrorException($error, $statusCode),
            };
        }

        if (isset($array['data'])) {
            return $array['data'];
        }

        if (!empty($array)) {
            return $array;
        }

        throw new EmptyDataException("Unexpected error", $response->getStatusCode());
    }
}
