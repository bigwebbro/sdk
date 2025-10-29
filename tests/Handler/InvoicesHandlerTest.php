<?php

declare(strict_types=1);

namespace Handler;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientExceptionDecorator;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\GuzzleHttpClientBuilder;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Handler\InvoicesHandler;
use Tiyn\MerchantApiSdk\Handler\ResponseHandler;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Model\Invoices\CreateInvoices;

class InvoicesHandlerTest extends TestCase
{
    public const INVOICE_UUID = "1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a";
    public const INVOICE_EXTERNAL_ID = "3c5301df-d806-4fb0-9f96-f44d5d2d3827";
    public const INVOICE_PAYMENT_LINK = "https://payment";
    public const ERROR_CODE = "-1";
    public const ERROR_UNAUTHORIZED_MESSAGE = "Unauthorized";
    public const ERROR_FORBIDDEN_MESSAGE = "Forbidden";
    public const ERROR_CORRELATION_ID = "9f63d8d9-4260-432f-a47d-3eead8a3c6e7";

    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $this->logger = $logger;
    }

    /**
     * @test
     * @dataProvider mockHandlerProvider
     * @covers \Tiyn\MerchantApiSdk\Handler\InvoicesHandler
     */
    public function createInvoicesSuccessCreatedTest(?string $exception, MockHandler $mock): void
    {
        $client = (new GuzzleHttpClientBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->setApiKey('test-api-key')
            ->setOptions(['handler' => HandlerStack::create($mock)])
            ->build();

        $exceptionDecorator = new HttpClientExceptionDecorator($client);
        $loggerDecorator = new HttpClientLoggingDecorator($exceptionDecorator);
        $loggerDecorator->setLogger($this->logger);
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $handler = new InvoicesHandler(
            $loggerDecorator,
            Validation::createValidator(),
            $serializer,
            $serializer,
            new ResponseHandler($serializer, $serializer),
            'secret'
        );

        if (null !== $exception) {
            $this->expectException($exception);
        }

        switch ($exception) {
            case ApiKeyException::class:
                $e = new ApiKeyException(
                    new Error(
                        self::ERROR_CODE,
                        self::ERROR_UNAUTHORIZED_MESSAGE,
                        self::ERROR_CORRELATION_ID
                    ),
                    401
                );
                $this->expectExceptionObject($e);
                break;
            case SignException::class:
                $e = new SignException(
                    new Error(
                        self::ERROR_CODE,
                        self::ERROR_FORBIDDEN_MESSAGE,
                        self::ERROR_CORRELATION_ID
                    ),
                    403
                );
                $this->expectExceptionObject($e);
                break;
        }

        $invoicesData = $handler->createInvoices(new CreateInvoices());

        self::assertEquals($invoicesData->getUuid(), self::INVOICE_UUID);
        self::assertEquals($invoicesData->getExternalId(), self::INVOICE_EXTERNAL_ID);
        self::assertEquals($invoicesData->getPaymentLink(), self::INVOICE_PAYMENT_LINK);
    }

    /**
     * @return array<int, mixed>
     */
    public static function mockHandlerProvider(): array
    {
        return [
            // 200 & valid body
            [
                null,
                new MockHandler([
                    new Response(
                        200,
                        [],
                        \sprintf('{
                          "success": true,
                          "data": {
                            "uuid": "%s",
                            "externalId": "%s",
                            "paymentLink": "%s"
                          }
                        }', self::INVOICE_UUID, self::INVOICE_EXTERNAL_ID, self::INVOICE_PAYMENT_LINK)
                    ),
                ])
            ],
            // 200 & empty body
            [
                JsonProcessingException::class,
                new MockHandler([new Response(200, [], '')])
            ],
            [
                ApiKeyException::class,
                new MockHandler([new Response(401, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_CODE, self::ERROR_UNAUTHORIZED_MESSAGE, self::ERROR_CORRELATION_ID))])
            ],
            [
                SignException::class,
                new MockHandler([new Response(403, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_CODE, self::ERROR_FORBIDDEN_MESSAGE, self::ERROR_CORRELATION_ID))])
            ],
        ];
    }
}
