<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Details;

final class DetailsDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__details_denormalizer_running';

                /**
                 * @inheritDoc
                 * @phpstan-ignore-next-line
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if ($data['details'] && \is_array($data['details'])) {
                        $data['details'] = $this->denormalizer->denormalize($data['details'], Details::class, $format, $context);
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

                    return isset($data['details']);
                }

                /**
                 * @param string|null $format
                 * @return class-string[]
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        Details::class
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__details_denormalizer_running';

                /**
                 * @inheritDoc
                 * @phpstan-ignore-next-line
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if ($data['details'] && \is_array($data['details'])) {
                        $data['details'] = $this->denormalizer->denormalize($data['details'], Details::class, $format, $context);
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

                    return isset($data['details']);
                }
            };
        }
    }
}
