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
    const HANDLED_HTTP_4XX_CODES = [
        400,
        401,
        403,
        408,
        418,
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
            408 === $statusCode => new TimeoutException(\sprintf('%d Request Timeout', $statusCode), $statusCode),
            418 === $statusCode => new BlockedRequestException(\sprintf('%d Request was blocked', $statusCode), $statusCode),
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
                400 => new EntityErrorException($error, $statusCode),
                401 => new ApiKeyException($error, $statusCode),
                403 => new SignException($error, $statusCode),
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
