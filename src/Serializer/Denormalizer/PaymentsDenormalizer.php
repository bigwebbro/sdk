<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;

final class PaymentsDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__payments_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if ($data['payments'] && \is_array($data['payments'])) {
                        $data['payments'] = $this->denormalizer->denormalize($data['payments'], Payment::class . '[]', $format, $context);
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

                    return isset($data['payments']);
                }

                /**
                 * @inheritDoc
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        PaymentAwareDenormalizationInterface::class => false,
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__payments_denormalizer_running';


                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if ($data['payments'] && \is_array($data['payments'])) {
                        $data['payments'] = $this->denormalizer->denormalize($data['payments'], Payment::class . '[]', $format, $context);
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

                    return isset($data['payments']);
                }
            };
        }
    }
}
