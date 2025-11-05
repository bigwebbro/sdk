<?php

declare(strict_types=1);

namespace Tests\Service\Handler;

use Symfony\Component\Validator\Validation;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;
use Tiyn\MerchantApiSdk\Serializer\SerializerFactory;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandler;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handleUncompletedModelTest(): void
    {
        $handler = new RequestHandler(
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator(),
            SerializerFactory::create()
        );
        $invoiceRequest = (new CreateInvoiceRequest())
            ->setAmount('102')
        ;

        $this->expectException(ValidationException::class);

        $handler->handleRequest($invoiceRequest);
    }

    /**
     * @test
     */
    public function successHandleModelTest(): void
    {
        $handler = new RequestHandler(
            Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator(),
            SerializerFactory::create()
        );

        $invoiceRequest = (new CreateInvoiceRequest())
            ->setExternalId('id')
            ->setAmount('105.3')
            ->setDescription('description')
            ->setDeliveryMethod(DeliveryMethodEnum::default())
            ->setCustomerEmail('qwer@example.com')
            ->setCustomerPhone('+79999999999')
        ;

        $json = $handler->handleRequest($invoiceRequest);

        self::assertJsonStringEqualsJsonString('{
            "externalId":"id",
            "amount":105.3,
            "description":"description",
            "deliveryMethod":"URL",
            "customerEmail":"qwer@example.com",
            "customerPhone":"+79999999999"
        }', $json);
    }
}
