<?php

require_once 'vendor/autoload.php';

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;

// --- PropertyInfo для ObjectNormalizer ---

$extractor = new PropertyInfoExtractor(typeExtractors: [
    new PhpDocExtractor(),
    new ReflectionExtractor()
]);

$objectNormalizer = new ObjectNormalizer(
    propertyTypeExtractor: $extractor
);


// --- Кастомный денормалайзер для Payment[] ---
class PaymentsDenormalizer implements \Symfony\Component\Serializer\Normalizer\DenormalizerInterface
{
    public function __construct(private \Symfony\Component\Serializer\Normalizer\DenormalizerInterface $denormalizer)
    {
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if ($type === Payment::class . '[]' && is_array($data)) {
            foreach ($data as &$item) {
                $item = $this->denormalizer->denormalize($item, Payment::class, $format, $context);
            }
        }
        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Payment::class . '[]';
    }
}

// --- Serializer ---
$serializer = new Serializer(
    [
//        new StatusDenormalizer(),
//        new DateTimeDenormalizer(),
//        new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s.uP']),
//        new AmountDenormalizer(),
//        new AmountNormalizer(),
//        new PaymentsDenormalizer($objectNormalizer), // перед ArrayDenormalizer
        $objectNormalizer,
//        new ArrayDenormalizer(), // обязательно после ObjectNormalizer
    ],
    [new JsonEncoder()]
);

// --- Использование ---
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

$data = $serializer->deserialize($json, \Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse::class, 'json');
var_dump($data);
// $data->getPayments() теперь массив объектов Payment
