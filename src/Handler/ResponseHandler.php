<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Handler;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Exception\Validation\WrongDataException;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Exception\Validation\EmptyDataException;

class ResponseHandler implements ResponseHandlerInterface
{
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
        $body = (string) $response->getBody();
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

        throw new EmptyDataException("Unexpected error", $response->getStatusCode());
    }
}
