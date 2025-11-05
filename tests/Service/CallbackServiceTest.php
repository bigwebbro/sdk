<?php

declare(strict_types=1);

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Tests\Service\Trait\SetUpSdkTrait;
use Tiyn\MerchantApiSdk\Serializer\SerializerFactory;
use Tiyn\MerchantApiSdk\Sign\Sign;
use Tiyn\MerchantApiSdk\Sign\SignException;

class CallbackServiceTest extends TestCase
{
    use SetUpSdkTrait;

    public const SECRET_PHRASE = 'test';
    public const CALLBACK_BODY = <<<JSON
{
  "externalId": "3c5301df-d806-4fb0-9f96-f44d5d2d3827",
  "uuid": "1fd64b0c-a8e7-4dc1-a799-f0cfa3ebad3a",
  "amount": 105.05,
  "finalAmount": 123.12,
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

    /**
     * @test
     */
    public function callbackSuccessHandleJsonTest(): void
    {
        $this->setSecretPhrase();

        $callbackInvoice = $this->sdk->callback()->handleInvoiceCallback(
            Sign::hash(self::CALLBACK_BODY, self::SECRET_PHRASE),
            self::CALLBACK_BODY
        );
        $serializer = SerializerFactory::create();
        self::assertJsonStringEqualsJsonString(self::CALLBACK_BODY, $serializer->serialize($callbackInvoice, 'json'));
    }

    /**
     * @test
     */
    public function callbackBrokenXSignTest(): void
    {
        $this->setSecretPhrase();
        $sign = Sign::hash(self::CALLBACK_BODY, self::SECRET_PHRASE);
        $this->expectException(SignException::class);
        $callbackInvoice = $this->sdk->callback()->handleInvoiceCallback(
            $sign . ' ',
            self::CALLBACK_BODY
        );
        $serializer = SerializerFactory::create();
        self::assertJsonStringEqualsJsonString(self::CALLBACK_BODY, $serializer->serialize($callbackInvoice, 'json'));
    }

    private function setSecretPhrase(): void
    {
        $ref = (new \ReflectionObject($this->sdk));
        $invoiceProp = $ref->getProperty('callbackService');
        $invoiceService = $invoiceProp->getValue($this->sdk);
        $refService = new \ReflectionObject($invoiceService);
        $secretProp = $refService->getProperty('secretPhrase');
        $secretProp->setValue($invoiceService, self::SECRET_PHRASE);

    }
}
