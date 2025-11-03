<?php

declare(strict_types=1);

namespace Tests\Client\Guzzle;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Tiyn\MerchantApiSdk\Client\ClientBuilderInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Exception\Client\ClientConfigurationException;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;
use Tiyn\MerchantApiSdk\Client\Util\Clock\Clock;

class ClientBuilderTest extends TestCase
{
    private ClientBuilderInterface $clientBuilder;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $this->clientBuilder  = (new ClientBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->setApiKey('test-api-key')
            ->addDecorator(new ClientLoggingDecorator($logger, new Clock()))
        ;
    }

    /**
     * @test
     */
    public function clientDoRetryTest(): void
    {
        $client = $this->clientBuilder
            ->enableRetry(5, 0.0)
            ->setOptions(['handler' => HandlerStack::create(
                new MockHandler(
                    [
                        new Response(503, [], '503 Service Unavailable'),
                        new Response(200, [], 'Ok')
                    ]
                )
            )])
            ->build();

        $respone = $client->sendRequest(new Request('GET', 'http://example'));
        self::assertEquals(200, $respone->getStatusCode());
    }

    /**
     * @test
     */
    public function clientThrowExceptionClientConfigurationExceptionWhenRetryAttemptsGreaterThen5Test(): void
    {
        $this->expectException(ClientConfigurationException::class);

        $this->clientBuilder
            ->enableRetry(6, 0.0)
            ->build();

        $this->expectExceptionObject(new ClientConfigurationException());
    }
}
