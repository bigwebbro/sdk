<?php

declare(strict_types=1);

namespace Tests\Service;

use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Service\Trait\SetUpBuilderTrait;
use Tiyn\MerchantApiSdk\Exception\Api\ApiKeyException;
use Tiyn\MerchantApiSdk\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Exception\Api\SignException;
use Tiyn\MerchantApiSdk\Exception\Service\ServiceUnavailableException;
use Tiyn\MerchantApiSdk\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\CurrencyEnum;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;

class InvoiceServiceTest extends TestCase
{
    use SetUpBuilderTrait;

    public const INVOICE_UUID = "1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a";
    public const INVOICE_EXTERNAL_ID = "3c5301df-d806-4fb0-9f96-f44d5d2d3827";
    public const INVOICE_PAYMENT_LINK = "https://payment";
    public const ERROR_CODE = "-1";
    public const ERROR_ENTITY_CODE = "1";
    public const ERROR_UNAUTHORIZED_MESSAGE = "Unauthorized";
    public const ERROR_FORBIDDEN_MESSAGE = "Forbidden";
    public const ERROR_ENTITY_MESSAGE = "Invalid expiration date";
    public const ERROR_CORRELATION_ID = "9f63d8d9-4260-432f-a47d-3eead8a3c6e7";

    /**
     * @test
     * @dataProvider mockHandlerProvider
     */
    public function createInvoiceTest(?string $exception, MockHandler $mock): void
    {
        $sdk = $this
            ->sdkBuilder
            ->setClient(
                $this->client
                ->setOptions(['handler' => HandlerStack::create($mock)])
                ->build()
            )
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
            ->setExpirationDate((new \DateTimeImmutable())->add(new \DateInterval('P1D')));
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
     */
    public function getInvoiceTest(): void
    {
        $json = <<<JSON
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
              "ofdData": null,
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
                new Response(200, [], $json)
            ]
        );

        $sdk = $this
            ->sdkBuilder
            ->setClient(
                $this->client
                    ->setOptions(['handler' => HandlerStack::create($mock)])
                    ->build()
            )
            ->build();

        $invoiceRequest = new GetInvoiceRequest('asdasd');
        $invoice = $sdk->invoice()->getInvoice($invoiceRequest);
        self::assertEquals($invoice->getUuid(), self::INVOICE_UUID);
        self::assertEquals($invoice->getExternalId(), self::INVOICE_EXTERNAL_ID);

        foreach ($invoice->getPayments() as $payment) {
            self::assertInstanceOf(Payment::class, $payment);
        }
    }

    /**
     * @test
     * @covers \Tiyn\MerchantApiSdk\Service\InvoicesService::makeRefundByInvoice
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
            ->setClient(
                $this->client
                    ->setOptions(['handler' => HandlerStack::create($mock)])
                    ->build()
            )
            ->build();

        $createRefundRequest = (new CreateRefundRequest())
            ->setRequestId('requestId')
            ->setAmount("105.51")
            ->setReason("reason")
        ;

        $createdRefundResponse = $sdk->invoice()->makeRefundByInvoice($invoiceUuid, $createRefundRequest);
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
            // 500 & string body
            [
                ServiceUnavailableException::class,
                new MockHandler([new Response(500, [], '500 Service Unavailable')])
            ],
            [
                ConnectionException::class,
                new MockHandler([new GuzzleConnectException('111', new Request('GET', 'http://test'))])
            ],
        ];
    }
}
