<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer\DeliveryMethodNormalizationAwareInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

final class DeliveryMethodDenormalizer
{
    public static function create(): DenormalizerInterface
    {
        if (symfony_serializer_version() >= 7) {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__delivery_method_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['deliveryMethod'])) {
                        $data['deliveryMethod'] = DeliveryMethodEnum::tryFrom($data['deliveryMethod']);
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

                    return isset($data['deliveryMethod']);
                }

                /**
                 * @inheritDoc
                 */
                public function getSupportedTypes(?string $format): array
                {
                    return [
                        DeliveryMethodNormalizationAwareInterface::class => false,
                    ];
                }
            };
        } else {
            return new class () implements DenormalizerInterface, DenormalizerAwareInterface {
                use DenormalizerAwareTrait;

                private const CONTEXT_FLAG = '__delivery_method_denormalizer_running';

                /**
                 * @inheritDoc
                 */
                public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
                {
                    $context[self::CONTEXT_FLAG] = true;

                    if (isset($data['deliveryMethod'])) {
                        $data['deliveryMethod'] = DeliveryMethodEnum::tryFrom($data['deliveryMethod']);
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

                    return isset($data['deliveryMethod']);
                }
            };
        }
    }
}
