<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Tiyn\MerchantApiSdk\Configuration\SerializerFactory;

class DateTimeDenormalizer implements DenormalizerInterface
{
    public function __construct(private NormalizerInterface $normalizer) {}

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (isset($data['expirationDate'])) {
            $data['expirationDate'] = new \DateTimeImmutable($data['expirationDate']);
        }
        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_a($type, DateTimeDenormalizerAwareInterface::class, true)
            && (isset($data['expirationDate']) || isset($data['time']));
    }
}
