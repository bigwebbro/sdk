<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AmountNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $context[self::class] = true;

        $array = $this->normalizer->normalize($object, $format, $context);

        if (isset($array['amount'])) {
            $array['amount'] = $this->stringToFloat($array['amount']);
        }

        if (isset($array['finalAmount'])) {
            $array['finalAmount'] = $this->stringToFloat($array['finalAmount']);
        }

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!empty($context[self::class])) {
            return false;
        }

        return $data instanceof AmountNormalizationAwareInterface;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            AmountNormalizationAwareInterface::class => false,
        ];
    }

    private function stringToFloat(string $stringFloat): float
    {
        return round((float) $stringFloat, 2);
    }
}
