<?php

declare(strict_types=1);

namespace Tests\Service\Handler;

use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Tiyn\MerchantApiSdk\Client\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Model\Error;
use Tiyn\MerchantApiSdk\Serializer\SerializerFactory;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\UnauthorizedException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\EntityErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ForbiddenException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\BlockedRequestException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceUnavailableException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\TimeoutException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandler;

class ResponseHandlerTest extends TestCase
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

    /**
     * @test
     * @dataProvider responseProvider
     */
    public function successHandledErrorFromListTest(string $fcqn, ResponseInterface $response): void
    {
        $this->expectException($fcqn);

        $handler = new ResponseHandler(new JsonDecode(), SerializerFactory::create());
        $handler->handleResponse($response);

        switch ($fcqn) {
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
            case UnauthorizedException::class:
                $e = new UnauthorizedException(
                    new Error(
                        self::ERROR_CODE,
                        self::ERROR_UNAUTHORIZED_MESSAGE,
                        self::ERROR_CORRELATION_ID
                    ),
                    401
                );
                $this->expectExceptionObject($e);
                break;
            case ForbiddenException::class:
                $e = new ForbiddenException(
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
    }

    /**
     * @test
     */
    public function successHandleReceivedData(): void
    {
        $response = new Response(
            body: \sprintf('{
                          "success": true,
                          "data": {
                            "uuid": "%s",
                            "externalId": "%s",
                            "paymentLink": "%s"
                          }
                        }', self::INVOICE_UUID, self::INVOICE_EXTERNAL_ID, self::INVOICE_PAYMENT_LINK)
        );
        $handler = new ResponseHandler(new JsonDecode(), SerializerFactory::create());
        $invoiceData = $handler->handleResponse($response);

        self::assertEquals(self::INVOICE_UUID, $invoiceData['uuid']);
        self::assertEquals(self::INVOICE_EXTERNAL_ID, $invoiceData['externalId']);
        self::assertEquals(self::INVOICE_PAYMENT_LINK, $invoiceData['paymentLink']);
    }

    /**
     * @return array<int, mixed>
     */
    public static function responseProvider(): array
    {
        return [
            [
                DataTransformationException::class,
                new Response(200, [], ''),
            ],
            [
                EntityErrorException::class,
                new Response(400, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_ENTITY_CODE, self::ERROR_ENTITY_MESSAGE, self::ERROR_CORRELATION_ID))
            ],
            [
                UnauthorizedException::class,
                new Response(401, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_CODE, self::ERROR_UNAUTHORIZED_MESSAGE, self::ERROR_CORRELATION_ID))
            ],
            [
                ForbiddenException::class,
                new Response(403, [], \sprintf('{
                    "success": false,
                    "error": {
                        "code": "%s",
                        "message": "%s",
                        "correlationId": "%s"
                    }
                }', self::ERROR_CODE, self::ERROR_FORBIDDEN_MESSAGE, self::ERROR_CORRELATION_ID))
            ],
            [
                ServiceUnavailableException::class,
                new Response(503, [], '503 Service Unavailable')
            ],
            [
                TimeoutException::class,
                new Response(408, [], '')
            ],
            [
                BlockedRequestException::class,
                new Response(418, [], '')
            ],
            [
                ServiceException::class,
                new Response(419, [], '')
            ],
        ];
    }
}
