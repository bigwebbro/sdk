<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Configuration\SerializerFactory;

class DateTimeDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_FLAG = '__datetime_denormalizer_running';

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::CONTEXT_FLAG] = true;

        if (isset($data['expirationDate']) && is_string($data['expirationDate'])) {
            $data['expirationDate'] = \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $data['expirationDate']);
        }

        if (isset($data['time']) && is_string($data['time'])) {
            $data['time'] = \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $data['time']);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null, ...$args): bool
    {
        $context = $args[0] ?? [];

        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return isset($data['expirationDate']) || isset($data['time']);
    }
}
