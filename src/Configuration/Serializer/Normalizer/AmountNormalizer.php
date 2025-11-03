<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AmountNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const CONTEXT_FLAG = '__amount_normalizer_running';

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $context[self::CONTEXT_FLAG] = true;

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
     * @phpstan-ignore-next-line
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return $data instanceof AmountNormalizerAwareInterface;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            AmountNormalizerAwareInterface::class => false,
        ];
    }

    private function stringToFloat(string $stringFloat): float
    {
        return round((float) $stringFloat, 2);
    }
}
