<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Tiyn\MerchantApiSdk\Client\ClientBuilderInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientDecoratorAwareInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientExceptionDecorator;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;

final class ClientBuilder implements ClientBuilderInterface
{
    private string $baseUri;

    private int $timeout = 5;

    private string $apiKey;

    /**
     * @var array<string,mixed>
     */
    private array $options = [];

    /**
     * @var array<int, ClientDecoratorAwareInterface>
     */
    private array $decorators = [];

    public function setBaseUri(string $baseUri): ClientBuilderInterface
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function setTimeout(int $timeout): ClientBuilderInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setApiKey(string $apiKey): ClientBuilderInterface
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOptions(array $options): ClientBuilderInterface
    {
        $this->options = $options;

        return $this;
    }

    public function addDecorator(ClientInterface $decorator): ClientBuilderInterface
    {
        $this->decorators[] = $decorator;

        return $this;
    }

    public function build(): ClientInterface
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
            'http_errors' => false,
            'headers' => [
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            ...$this->options,
        ]);

        $exceptionDecorator = new ClientExceptionDecorator();
        $client = $exceptionDecorator->withClient($client);

        foreach ($this->decorators ?? [] as $decorator) {
            $client = $decorator->withClient($client);
        }

        return $client;
    }
}
