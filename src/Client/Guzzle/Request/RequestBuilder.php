<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle\Request;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Tiyn\MerchantApiSdk\Sign\Sign;

final class RequestBuilder
{
    private string $method;
    private string $endpoint;

    /**
     * @var string[]|string[][]
     */
    private array $headers = [];
    private ?string $body = null;

    private string $apiKey;

    public function withMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function withEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param string[]|string[][] $headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function withBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function buildWithSign(string $secretPhrase): PsrRequestInterface
    {
        return (new Request($this->method, $this->endpoint, $this->headers, $this->body))
            ->withAddedHeader('Content-Type', 'application/json')
            ->withAddedHeader('X-Sign', Sign::hash($this->body, $secretPhrase))
            ->withAddedHeader('X-Api-Key', $this->apiKey)
        ;
    }

    public function build(): PsrRequestInterface
    {
        return (new Request($this->method, $this->endpoint, $this->headers, $this->body))
            ->withAddedHeader('X-Api-Key', $this->apiKey)
        ;
    }
}
