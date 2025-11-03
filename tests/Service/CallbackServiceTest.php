<?php

declare(strict_types=1);

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Tests\Service\Trait\SetUpSdkTrait;
use Tiyn\MerchantApiSdk\Configuration\Serializer\SerializerFactory;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse;

class CallbackServiceTest extends TestCase
{
    use SetUpSdkTrait;

    /**
     * @test
     */
    public function callbackHandlerSuccessDenormalizeTest(): void
    {
        $json = <<<JSON
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
        $callbackInvoice = $this->sdk->callback()->handleInvoiceCallback($json);
        $serializer = SerializerFactory::init();
        self::assertJsonStringEqualsJsonString($json, $serializer->serialize($callbackInvoice, 'json'));
    }
}
