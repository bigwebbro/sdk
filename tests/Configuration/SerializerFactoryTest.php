<?php

declare(strict_types=1);

namespace Configuration;

use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Configuration\SerializerFactory;
use PHPUnit\Framework\TestCase;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundResponse;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;

class SerializerFactoryTest extends TestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->serializer = SerializerFactory::init();
    }

    /**
     * @test
     */
    public function SuccessSerializationWhenObjectWithoutGettersTest(): void
    {
        $expectedJson = '{"requestId":"requestId","reason":"reason"}';
        $createRefundRequest = (new CreateRefundRequest())
            ->setRequestId('requestId')
            ->setReason('reason')
        ;

        $result = $this->serializer->serialize($createRefundRequest, 'json');

        self::assertJsonStringEqualsJsonString($expectedJson, $result);
    }

    /**
     * @test
     */
    public function successDeserializationWhenObjectWithoutSettersTest(): void
    {
        $requestId = 'requestId';
        $uuid = '123';

        $result = $this->serializer->deserialize(
            \sprintf('{"requestId":"%s","uuid":"%s"}', $requestId, $uuid),
            CreateRefundResponse::class,
            'json'
        );

        self::assertEquals($requestId, $result->getRequestId());
        self::assertEquals($uuid, $result->getUuid());
    }

    /**
     * @test
     * @dataProvider amountProvider
     */
    public function SerializationAmountToFloat(string $stringAmount, float $floatAmount): void
    {
        $expectedJson = \sprintf('{"requestId":"requestId","amount":%f,"reason":"reason"}', $floatAmount);
        $createRefundRequest = (new CreateRefundRequest())
            ->setRequestId('requestId')
            ->setAmount($stringAmount)
            ->setReason('reason')
        ;

        $result = $this->serializer->serialize($createRefundRequest, 'json');

        self::assertJsonStringEqualsJsonString($expectedJson, $result);
    }

    /**
     * @test
     * @dataProvider amountProvider
     */
    public function DeserializationAmountToString(string $stringAmount, float $floatAmount): void
    {
        $result = $this->serializer->deserialize(
            \sprintf('{"requestId":"requestId","amount":%f,"reason":"reason"}', $floatAmount),
            CreateRefundRequest::class,
            'json'
        );

        $property = (new \ReflectionClass($result))->getProperty('amount');

        self::assertIsString($property->getValue($result));
        self::assertEquals((string) round((float) $stringAmount, 2), $property->getValue($result));
    }

    public static function amountProvider(): array
    {
        return [
            ['13.1', 13.1],
            ['2444.444444', 2444.44],
            ['2444.445', 2444.45],
        ];
    }

    /**
     * @test
     */
    public function SerializationDateTimeToString(): void
    {
        $dateTimeStr = '2025-11-09 11:08:24.909150+03:00';
        $expectedJson = \sprintf('{"expirationDate":"%s"}', $dateTimeStr);
        $date = \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $dateTimeStr);
        $invoice = (new CreateInvoiceRequest())->setExpirationDate($date);
        $result = $this->serializer->serialize(
            $invoice,
            'json',
            [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
        );

        self::assertJsonStringEqualsJsonString($expectedJson, $result);
    }

    /**
     * @test
     */
    public function DeserializationStringToDateTime(): void
    {
        $dateTimeStr = '2025-11-09 11:08:24.909150+03:00';
        $result = $this->serializer->deserialize(
            \sprintf('{"expirationDate":"%s"}', $dateTimeStr),
            CreateInvoiceRequest::class,
            'json'
        );

        $property = (new \ReflectionClass($result))->getProperty('expirationDate');

        self::assertEquals(
            \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $dateTimeStr),
            $property->getValue($result)
        );
    }
}
