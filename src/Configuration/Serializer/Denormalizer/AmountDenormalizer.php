<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class AmountDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__amount_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['amount'])) {
                        $data['amount'] = (string) $data['amount'];
                    }

                    if (isset($data['finalAmount'])) {
                        $data['finalAmount'] = (string) $data['finalAmount'];
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

                    return is_a($type, AmountDenormalizerAwareInterface::class, true)
                        && (isset($data['amount']) || isset($data['']));
                }

                /**
                 * @inheritDoc
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        AmountDenormalizerAwareInterface::class => false,
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__amount_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['amount'])) {
                        $data['amount'] = (string) $data['amount'];
                    }

                    if (isset($data['finalAmount'])) {
                        $data['finalAmount'] = (string) $data['finalAmount'];
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

                    return is_a($type, AmountDenormalizerAwareInterface::class, true)
                        && (isset($data['amount']) || isset($data['finalAmount']));
                }
            };
        }
    }
}
