<?php

declare(strict_types=1);

namespace Configuration;

use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Configuration\SerializerFactory;
use PHPUnit\Framework\TestCase;
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
        $expectedJson = '{"requestId":"requestId","amount":105.51,"reason":"reason"}';
        $createRefundRequest = (new CreateRefundRequest())
            ->setRequestId('requestId')
            ->setAmount("105.51")
            ->setReason("reason")
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

        $result = $this->serializer->deserialize(\sprintf('{"requestId":"%s","uuid":"%s"}', $requestId, $uuid), CreateRefundResponse::class, 'json');

        self::assertEquals($requestId, $result->getRequestId());
        self::assertEquals($uuid, $result->getUuid());
    }

}
