<?php

declare(strict_types=1);

namespace Tests\Handler;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\CurrencyEnum;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;

class InvoicesHandlerTest extends TestCase
{
    public const INVOICE_UUID = "1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a";
    public const INVOICE_EXTERNAL_ID = "3c5301df-d806-4fb0-9f96-f44d5d2d3827";
    public const INVOICE_PAYMENT_LINK = "https://payment";
    public const ERROR_CODE = "-1";
    public const ERROR_ENTITY_CODE = "1";
    public const ERROR_UNAUTHORIZED_MESSAGE = "Unauthorized";
    public const ERROR_FORBIDDEN_MESSAGE = "Forbidden";
    public const ERROR_ENTITY_MESSAGE = "Invalid expiration date";
    public const ERROR_CORRELATION_ID = "9f63d8d9-4260-432f-a47d-3eead8a3c6e7";

    private MerchantApiSdkBuilder $sdkBuilder;

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $builder = (new MerchantApiSdkBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->setApiKey('test-api-key')
            ->addHttpApiClientDecorator(HttpClientLoggingDecorator::class)
            ->setSecretPhrase('test-secret-phrase');
        $builder->setLogger($logger);

        $this->sdkBuilder = $builder;

        $serializebuilder = SerializerBuilder::create();
        $serializebuilder->setPropertyNamingStrategy(new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy());
        $serializebuilder->configureHandlers(function (\JMS\Serializer\Handler\HandlerRegistry $registry): void {
            $registry->registerSubscribingHandler(new MyHandler());
        });

        $this->serializer = $serializebuilder->build();
    }

    /**
     * @test
     * @dataProvider mockHandlerProvider
     * @covers \Tiyn\MerchantApiSdk\Service\InvoicesService::createInvoice
     */
    public function createInvoiceTest(?string $exception, MockHandler $mock): void
    {
        $sdk = $this
            ->sdkBuilder
            ->setClientOptions(['handler' => HandlerStack::create($mock)])
            ->build();

        if (null !== $exception) {
            $this->expectException($exception);
        }

        $invoiceRequest = (new CreateInvoiceRequest())
            ->setExternalId('1')
            ->setAmount('104.55')
            ->setCurrency(CurrencyEnum::KZT->value)
            ->setDescription('test')
            ->setDeliveryMethod(DeliveryMethodEnum::URL->value)
            ->setExpirationDate(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', '2026-03-14 11:08:24.909150+03:00'));
        $invoicesData = $sdk->invoice()->createInvoice($invoiceRequest);

        switch ($exception) {
            case EntityErrorException::class:
                $e = new EntityErrorException(
                    new Error(
                        self::ERROR_ENTITY_CODE,
                        self::ERROR_ENTITY_MESSAGE,
                        self::ERROR_CORRELATION_ID
                    ),
                    400
                );
                $this->expectExceptionObject($e);
                break;
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

        self::assertEquals($invoicesData->getUuid(), self::INVOICE_UUID);
        self::assertEquals($invoicesData->getExternalId(), self::INVOICE_EXTERNAL_ID);
        self::assertEquals($invoicesData->getPaymentLink(), self::INVOICE_PAYMENT_LINK);
    }

    /**
     * @test
     * @covers \Tiyn\MerchantApiSdk\Service\InvoicesService::getInvoice
     */
    public function getInvoiceTest(): void
    {
        $returnedJson = <<<JSON
            {
              "externalId": "3c5301df-d806-4fb0-9f96-f44d5d2d3827",
              "uuid": "1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a",
              "amount": "105.05",
              "finalAmount": "123.12",
              "currency": "KZT",
              "description": "Счет на оплату заказа №12080",
              "customerPhone": "+74994550185",
              "customerEmail": "support@tiyn.io",
              "customData": {
                "key1": "value1",
                "key2": 5
              },
              "successUrl": "http://empty.com/successUrl",
              "failUrl": "http://empty.com/failUrl",
              "deliveryMethod": "URL",
              "expirationDate": "2021-03-14 11:08:24.909150+03:00",
              "ofdData": ["data"],
              "status": {
                "name": "InvoicePaid",
                "time": "2020-03-14 11:08:24.909150+03:00",
                "message": "message"
              },
              "payments": [
                {
                  "paymentMethod": "BankCard",
                  "details": {
                    "account": "411111******1111",
                    "paymentToken": "837c06b4-b791-4f2c-89b1-a45f78cb1568"
                  },
                  "status": {
                    "name": "PaymentPaid"
                  }
                }
              ]
            }
        JSON;

        $mock = new MockHandler(
            [
                new Response(200, [], $returnedJson)
            ]
        );
        $sdk = $this
            ->sdkBuilder
            ->setClientOptions(['handler' => HandlerStack::create($mock)])
            ->build();

        $getInvoiceRequest = new GetInvoiceRequest('asdasd');
        $invoicesData = $sdk->invoice()->getInvoice($getInvoiceRequest);
        $json = $this->serializer->serialize($invoicesData, 'json');
        self::assertJsonStringEqualsJsonString($returnedJson, $json);
    }

    /**
     * @test
     * @covers \Tiyn\MerchantApiSdk\Service\InvoicesService::createRefund
     */
    public function createRefundTest(): void
    {
        $invoiceUuid = '1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a';
        $requestId = 'd1f6b55c-e8a1-add7-a719-a1cfd3eda3ad';
        $returnedJson = <<<JSON
            {
              "success": true,
              "data": {
                "uuid": "$invoiceUuid",
                "requestId": "$requestId"
              }
            }
        JSON;

        $mock = new MockHandler(
            [
                new Response(200, [], $returnedJson)
            ]
        );
        $sdk = $this
            ->sdkBuilder
            ->setClientOptions(['handler' => HandlerStack::create($mock)])
            ->build();

        $createRefundRequest = (new CreateRefundRequest())
            ->setRequestId('requestId')
            ->setAmount("105.51")
            ->setReason("reason")
        ;

        $createdRefundResponse = $sdk->invoice()->createRefund($invoiceUuid, $createRefundRequest);
        self::assertEquals($invoiceUuid, $createdRefundResponse->getUuid());
        self::assertEquals($requestId, $createdRefundResponse->getRequestId());
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
                EntityErrorException::class,
                new MockHandler([new Response(400, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_ENTITY_CODE, self::ERROR_ENTITY_MESSAGE, self::ERROR_CORRELATION_ID))])
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
