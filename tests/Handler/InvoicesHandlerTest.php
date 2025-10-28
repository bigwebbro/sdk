<?php

declare(strict_types=1);

namespace Handler;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\GuzzleHttpClientBuilder;
use Tiyn\MerchantApiSdk\Handler\InvoicesHandler;
use PHPUnit\Framework\TestCase;
use Tiyn\MerchantApiSdk\Model\CreateInvoices;

class InvoicesHandlerTest extends TestCase
{
    private InvoicesHandler $handler;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
//            new Response(202, ['Content-Length' => 0]),
//            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = (new GuzzleHttpClientBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->setApiKey('test-api-key')
            ->setOptions(['handler' => $handlerStack])
            ->build()
        ;

        $decoratedClient = new HttpClientLoggingDecorator($client);
        $decoratedClient->setLogger($logger);

        $this->handler = new InvoicesHandler(
            $decoratedClient,
            Validation::createValidator(),
            new Serializer([new ObjectNormalizer()], [new JsonEncoder()]),
            'secret'
        );
    }

    /**
     * @test
     */
    public function createInvoicesTest(): void
    {
        $this->handler->createInvoices(new CreateInvoices());
    }
}
