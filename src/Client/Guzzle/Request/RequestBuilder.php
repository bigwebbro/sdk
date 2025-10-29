<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle\Request;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Tiyn\MerchantApiSdk\Client\Sign\Sign;

class RequestBuilder
{
    private string $method;
    private string $endpoint;

    /**
     * @var string[]|string[][]
     */
    private array $headers;
    private string $body;

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

    public function buildWithSign(string $secretPhrase): PsrRequestInterface
    {
        $request = new Request($this->method, $this->endpoint, $this->headers, $this->body);
        $request->withAddedHeader('X-Sign', Sign::hash($this->body, $secretPhrase));

        return $request;
    }
}
