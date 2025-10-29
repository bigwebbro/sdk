<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Tiyn\MerchantApiSdk\Client\HttpClientBuilderInterface;

final class GuzzleHttpClientBuilder implements HttpClientBuilderInterface
{
    private string $baseUri;

    private int $timeout;

    private string $apiKey;

    /**
     * @var array<string,mixed>
     */
    private array $options;

    public function setBaseUri(string $baseUri): HttpClientBuilderInterface
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function setTimeout(int $timeout): HttpClientBuilderInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setApiKey(string $apiKey): HttpClientBuilderInterface
    {
        $this->apiKey = $apiKey;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function setOptions(array $options): HttpClientBuilderInterface
    {
        $this->options = $options;

        return $this;
    }


    public function build(): ClientInterface
    {
        return new Client([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
            'http_errors' => false,
            'headers' => ['X-Api-Key' => $this->apiKey],
            ...$this->options,
        ]);
    }
}
