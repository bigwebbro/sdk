<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Validator\Validation;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\GuzzleHttpClientBuilder;
use Tiyn\MerchantApiSdk\Client\HttpClientBuilderInterface;
use Tiyn\MerchantApiSdk\Handler\InvoicesHandler;
use Tiyn\MerchantApiSdk\Validator\ValidatorAwareInterface;
use Tiyn\MerchantApiSdk\Validator\ValidatorAwareTrait;

final class MerchantApiSdkBuilder implements SerializerAwareInterface, LoggerAwareInterface, ValidatorAwareInterface
{
    use ValidatorAwareTrait;
    use LoggerAwareTrait;
    use SerializerAwareTrait;

    private array $decorators = [];

    private ?HttpClientBuilderInterface $builder = null;

    private int $timeout;

    private string $baseUri;

    private string $apiKey;

    private string $secretPhrase;

    public function addHttpApiClientDecorator(string $fqcn): self
    {
        $this->decorators[] = $fqcn;

        return $this;
    }

    public function setHttpClientBuilder(HttpClientBuilderInterface $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setSecretPhrase(string $secretPhrase): self
    {
        $this->secretPhrase = $secretPhrase;

        return $this;
    }

    public function build(): MerchantApiSdk
    {
        if (null === $this->builder) {
            $this->builder = new GuzzleHttpClientBuilder();
        }

        $client = $this->builder
            ->setBaseUri($this->baseUri)
            ->setApiKey($this->apiKey)
            ->setTimeout($this->timeout)
        ;

        if (!empty($this->decorators)) {
            foreach ($this->decorators as $decorator) {
                $client = new $decorator($client);

                if (HttpClientLoggingDecorator::class === $decorator) {
                    $decorator->setLogger($this->logger);
                }
            }
        }

        if (null === $this->validator) {
            $this->validator = Validation::createValidator();
        }

        if (!isset($this->serializer)) {
            $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        }


        $invoicesHandler = new InvoicesHandler($client, $this->validator, $this->serializer, $this->secretPhrase);

        return new MerchantApiSdk($invoicesHandler);
    }
}
