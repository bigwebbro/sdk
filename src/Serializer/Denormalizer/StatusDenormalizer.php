<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Status;

final class StatusDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__status_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['status'])) {
                        $data['status'] = $this->denormalizer->denormalize($data['status'], Status::class);
                    }
                    return $this->denormalizer->denormalize($data, $type, $format, $context);
                }

                /**
                 * @inheritDoc
                 */
                public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
                {
                    if (!empty($context[self::CONTEXT_FLAG])) {
                        return false;
                    }

                    return isset($data['status']);
                }

                /**
                 * @inheritDoc
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        StatusAwareDenormalizationInterface::class => false,
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__status_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['status'])) {
                        $data['status'] = $this->denormalizer->denormalize($data['status'], Status::class);
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

                    return isset($data['status']);
                }
            };
        }
    }
}
