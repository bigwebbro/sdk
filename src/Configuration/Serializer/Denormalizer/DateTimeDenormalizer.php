<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Configuration\Serializer\SerializerFactory;

final class DateTimeDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__datetime_denormalizer_running';

                /**
                 * @inheritDoc
                 * @phpstan-ignore-next-line
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
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
                public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
                {
                    if (!empty($context[self::CONTEXT_FLAG])) {
                        return false;
                    }

                    return isset($data['expirationDate']) || isset($data['time']);
                }

                /**
                 * @return array<class-string, bool>
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        DateTimeAwareDenormalizationInterface::class => false,
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__datetime_denormalizer_running';

                /**
                 * @inheritDoc
                 * @phpstan-ignore-next-line
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
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
                 */
                public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
                {
                    $args = \func_get_args();
                    $context = $args[3] ?? [];

                    if (!empty($context[self::CONTEXT_FLAG])) {
                        return false;
                    }

                    return isset($data['expirationDate']) || isset($data['time']);
                }
            };
        }
    }
}
