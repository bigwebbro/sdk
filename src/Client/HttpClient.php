<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class HttpClient implements ClientInterface
{
    public function __construct(
        private ClientInterface $httpClient,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
