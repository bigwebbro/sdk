<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
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
use Tiyn\MerchantApiSdk\Configuration\Normalizer\DetailsDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\PaymentsDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\StatusDenormalizer;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Details;

class SerializerFactory
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s.uP';

    public static function init(): SerializerInterface | NormalizerInterface | DenormalizerInterface
    {
        $extractor = new PropertyInfoExtractor(typeExtractors: [
            new PhpDocExtractor(),
            new ReflectionExtractor(),
        ]);

        $objectNormalizer = new ObjectNormalizer(
            propertyTypeExtractor: $extractor,
        );

        return new Serializer(
            [
                new DetailsDenormalizer(),
                new PaymentsDenormalizer(),
                new StatusDenormalizer(),
                new DateTimeDenormalizer(),
                new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => self::DATE_TIME_FORMAT]),
                new AmountDenormalizer(),
                new AmountNormalizer(),
                new PropertyNormalizer(),
                new ArrayDenormalizer(),
                $objectNormalizer,
            ],
            [new JsonEncoder()]
        );
    }
}
