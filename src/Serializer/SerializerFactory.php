<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Serializer;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\AmountDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\DateTimeDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\DeliveryMethodDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\DetailsDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\PaymentsDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\StatusDenormalizer;
use Tiyn\MerchantApiSdk\Serializer\Normalizer\AmountNormalizer;
use Tiyn\MerchantApiSdk\Serializer\Normalizer\DeliveryMethodNormalizer;

final class SerializerFactory
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s.uP';

    public static function create(): SerializerInterface|NormalizerInterface|DenormalizerInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $extractor = new PropertyInfoExtractor(typeExtractors: [
            new PhpDocExtractor(),
            $reflectionExtractor,
        ]);

        $objectNormalizer = new ObjectNormalizer(
            propertyTypeExtractor: $extractor,
        );
        $propertyNormalizer = new PropertyNormalizer(
            propertyTypeExtractor: $extractor,
        );

        return new Serializer(
            [
                new BackedEnumNormalizer(),
//                DetailsDenormalizer::create(),
//                PaymentsDenormalizer::create(),
//                StatusDenormalizer::create(),
//                DateTimeDenormalizer::create(),
//                DeliveryMethodDenormalizer::create(),
                new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => self::DATE_TIME_FORMAT]),
                AmountDenormalizer::create(),
                new AmountNormalizer(),
//                new DeliveryMethodNormalizer(),
                new ArrayDenormalizer(),
                $propertyNormalizer,
                $objectNormalizer,
            ],
            [
                new JsonEncoder(defaultContext: [
                    'json_encode_options' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ])
            ]
        );
    }
}
