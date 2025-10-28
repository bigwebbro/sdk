<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Guzzle\Request;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Tiyn\MerchantApiSdk\Exception\JsonProcessingException;
use Tiyn\MerchantApiSdk\Model\ObjectNormalize;
use Tiyn\MerchantApiSdk\Client\Guzzle\Request\RequestSignable;
use Tiyn\MerchantApiSdk\Client\Sign\Sign;

class RequestBuilder
{
    private string $method;
    private string $endpoint;
    private array $headers;
    private ObjectNormalize $obj;


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

    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function withBody(ObjectNormalize $obj): self
    {
        $this->obj = $obj;

        return $this;
    }

    public function buildWithSign(string $secretPhrase): PsrRequestInterface
    {
        try {
            $json = json_encode($this->obj->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (\JsonException $e) {
            throw new JsonProcessingException($e->getMessage(), $e->getCode(), $e);
        }

        $request = new Request($this->method, $this->endpoint, $this->headers, $json);
        $request->withAddedHeader('X-Sign', Sign::hash($json, $secretPhrase));
        var_dump(Sign::hash($json, $secretPhrase));
        return $request;
    }
}
