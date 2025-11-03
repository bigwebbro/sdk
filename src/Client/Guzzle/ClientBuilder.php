<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Http\Client\ClientInterface;
use Tiyn\MerchantApiSdk\Client\ClientBuilderInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientDecoratorAwareInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientExceptionDecorator;
use Tiyn\MerchantApiSdk\Client\Exception\Client\ClientConfigurationException;

final class ClientBuilder implements ClientBuilderInterface
{
    private string $baseUri;

    private int $timeout = 5;

    private string $apiKey;

    private int $maxAttempts;

    private float $multiplier;

    private bool $enableRetry = false;

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

    /**
     * @param int $maxAttempts
     * @param float $multiplier uses if response hasn't Retry-After header
     * @return ClientBuilderInterface
     */
    public function enableRetry(int $maxAttempts, float $multiplier): ClientBuilderInterface
    {
        if ($maxAttempts > 5) {
            throw new ClientConfigurationException('Max retry attempts should be less or equals 5');
        }

        $this->maxAttempts = $maxAttempts;
        $this->multiplier = $multiplier;
        $this->enableRetry = true;

        return $this;
    }

    public function build(): ClientInterface
    {
        $stack = null;

        if (!empty($this->options['handler'])) {
            $stack = HandlerStack::create($this->options['handler']);
            unset($this->options['handler']);
        }

        if ($this->enableRetry) {
            $stack ??= HandlerStack::create();
            $stack->push(GuzzleRetryMiddleware::factory(
                [
                    'max_retry_attempts' => $this->maxAttempts,
                    'default_retry_multiplier' => $this->multiplier,
                    'retry_on_status' => [408, 429, 500, 502, 503,]
                ]
            ));
        }

        $cfg = [
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
            'http_errors' => false,
            'headers' => [
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            ...$this->options,
        ];

        if ($stack instanceof HandlerStack) {
            $cfg['handler'] = $stack;
        }

        $client = new Client($cfg);

        $exceptionDecorator = new ClientExceptionDecorator();
        $client = $exceptionDecorator->withClient($client);

        foreach ($this->decorators ?? [] as $decorator) {
            $client = $decorator->withClient($client);
        }

        return $client;
    }
}
