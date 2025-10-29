<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Exception\Transport\ConnectionException;

final class HttpClientExceptionDecorator implements ClientInterface
{
    public function __construct(
        private readonly ClientInterface $inner,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->inner->sendRequest($request);
        } catch (NetworkExceptionInterface $e) {
            throw new ConnectionException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
