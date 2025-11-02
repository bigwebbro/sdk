<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Configuration\Serializer\SerializerFactory;

class DateTimeDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_FLAG = '__datetime_denormalizer_running';

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::CONTEXT_FLAG] = true;

        if (isset($data['expirationDate']) && \is_string($data['expirationDate'])) {
            $data['expirationDate'] = \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $data['expirationDate']);
        }

        if (isset($data['time']) && \is_string($data['time'])) {
            $data['time'] = \DateTimeImmutable::createFromFormat(SerializerFactory::DATE_TIME_FORMAT, $data['time']);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function supportsDenormalization($data, $type, $format = null, ...$args): bool
    {
        $context = $args[0] ?? [];

        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return isset($data['expirationDate']) || isset($data['time']);
    }
}
