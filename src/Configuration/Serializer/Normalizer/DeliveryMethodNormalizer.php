<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;

final class DeliveryMethodNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $context[__CLASS__] = true;

        $array = $this->normalizer->normalize($object, $format, $context);

        if (isset($array['deliveryMethod'])) {
            $array['deliveryMethod'] = $array['deliveryMethod'][array_key_last($array['deliveryMethod'])];
        }

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!empty($context[__CLASS__])) {
            return false;
        }

        return $data instanceof DeliveryMethodNormalizationAwareInterface;
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
}
