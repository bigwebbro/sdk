<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\AmountDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\DateTimeDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\DetailsDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\PaymentsDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\StatusDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer\AmountNormalizer;

final class SerializerFactory
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s.uP';

    public static function init(): SerializerInterface | NormalizerInterface |DenormalizerInterface
    {
        $extractor = new PropertyInfoExtractor(typeExtractors: [
            new ReflectionExtractor(),
        ]);

        $objectNormalizer = new ObjectNormalizer(
            propertyTypeExtractor: $extractor,
        );

        return new Serializer(
            [
                DetailsDenormalizer::create(),
                new PaymentsDenormalizer(),
                new StatusDenormalizer(),
                new DateTimeDenormalizer(),
                new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => self::DATE_TIME_FORMAT]),
                AmountDenormalizer::create(),
                new AmountNormalizer(),
                new PropertyNormalizer(),
                new ArrayDenormalizer(),
                $objectNormalizer,
            ],
            [new JsonEncoder()]
        );
    }
}
