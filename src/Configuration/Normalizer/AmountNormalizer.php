<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AmountNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param mixed $object
     * @param string|null $format
     * @param string[] $context
     * @return array<string, mixed>
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $context['__amount_normalizer_running'] = true;

        $array = $this->normalizer->normalize($object, $format, $context);
        if (isset($array['amount'])) {
            $array['amount'] = $this->stringToFloat($array['amount']);
        }

        return $array;
    }

    public function supportsNormalization(mixed $data, string $format = null, ...$args): bool
    {
        $context = $args[0] ?? [];

        if (!empty($context['__amount_normalizer_running'])) {
            return false;
        }

        return $data instanceof AmountNormalizerAwareInterface;
    }

    private function stringToFloat(string $stringFloat): float
    {
        return round((float) $stringFloat, 2);
    }
}
