<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Tiyn\MerchantApiSdk\Client\Util\Clock\ClockInterface;

final class ClientLoggingDecorator implements ClientInterface, ClientDecoratorAwareInterface
{
    use ClientDecoratorAwareTrait;

    private ClientInterface $inner;

    public function __construct(private readonly LoggerInterface $logger, private readonly ClockInterface $clock)
    {
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->logger->info('Request to Merchant API', [
            'method' => $request->getMethod(),
            'endpoint' => $request->getUri()->__toString()
        ]);

        try {
            $this->clock->start();
            $response = $this->inner->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->logger->error('Error occur while request to Merchant API', [
                'message' => $e->getMessage(),
                'http_code' => $e->getCode(),
                'time' => $this->clock->durationAsStringMs(),
            ]);

            throw $e;
        }

        $this->logger->info('Response from Merchant API', [
            'http_code' => $response->getStatusCode(),
            'time' => $this->clock->durationAsStringMs(),
        ]);

        return $response;
    }
}
