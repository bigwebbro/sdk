<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Serializer\SerializerFactory;
use Tiyn\MerchantApiSdk\Service\CallbackService;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandler;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandler;
use Tiyn\MerchantApiSdk\Service\InvoicesService;

final class MerchantApiSdkBuilder implements MerchantApiSdkBuilderInterface
{
    private SerializerInterface | DenormalizerInterface $serializer;

    private ValidatorInterface $validator;

    private DenormalizerInterface $denormalizer;

    private ClientInterface $client;

    private string $secretPhrase;

    private string $apiKey;

    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function setSecretPhrase(string $secretPhrase): self
    {
        $this->secretPhrase = $secretPhrase;

        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setSerializer(SerializerInterface $serializer): MerchantApiSdkBuilder
    {
        $this->serializer = $serializer;
        return $this;
    }

    public function setValidator(ValidatorInterface $validator): MerchantApiSdkBuilder
    {
        $this->validator = $validator;
        return $this;
    }

    public function build(): MerchantApiSdkInterface
    {
        if (!isset($this->serializer)) {
            $this->serializer = SerializerFactory::create();
            $this->denormalizer = $this->serializer;
        }

        if (!isset($this->validator)) {
            $this->validator = Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator();
        }

        $invoiceService = new InvoicesService(
            $this->client,
            $this->denormalizer,
            new RequestHandler($this->validator, $this->serializer),
            new ResponseHandler(new JsonDecode(), $this->denormalizer),
            $this->secretPhrase,
            $this->apiKey,
        );

        $callbackService = new CallbackService($this->serializer, $this->secretPhrase);

        return new MerchantApiSdk($invoiceService, $callbackService);
    }
}
