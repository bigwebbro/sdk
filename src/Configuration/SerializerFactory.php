<?php

namespace Tiyn\MerchantApiSdk\Configuration;

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
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountNormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\DateTimeDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\StatusDenormalizer;

class SerializerFactory
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s.uP';

    public static function init(): SerializerInterface | NormalizerInterface | DenormalizerInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $extractor = new PropertyInfoExtractor(typeExtractors: [$reflectionExtractor]);
        $objectNormalizer = new ObjectNormalizer(
            propertyInfoExtractor: $extractor,
        );

        return new Serializer(
            [
                new StatusDenormalizer($objectNormalizer),
                new DateTimeDenormalizer($objectNormalizer),
                new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => self::DATE_TIME_FORMAT]),
                new PropertyNormalizer(),
                new AmountDenormalizer(),
                new AmountNormalizer(),
                new ArrayDenormalizer(),
                $objectNormalizer,
            ],
            [new JsonEncoder()]
        );
    }
}