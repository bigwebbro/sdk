<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tiyn\MerchantApiSdk\Exception\Transport\ConnectionException;

final class ClientExceptionDecorator implements ClientInterface, ClientDecoratorAwareInterface
{
    use ClientDecoratorAwareTrait;

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
