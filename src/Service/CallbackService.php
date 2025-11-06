<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Sign\Sign;
use Tiyn\MerchantApiSdk\Sign\SignException;

final class CallbackService extends AbstractService implements CallbackServiceInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        string                               $secretPhrase,
    ) {
        parent::__construct($secretPhrase);
    }

    public function handleInvoiceCallback(string $xSign, string $body): GetInvoiceResponse
    {
        if (!hash_equals(Sign::hash($body, $this->secretPhrase), $xSign)) {
            throw new SignException('Callback has broken sign');
        }

        try {
            $result = $this->serializer->deserialize($body, GetInvoiceResponse::class, 'json');
        } catch (ExceptionInterface $e) {
            throw new DataTransformationException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }
}
