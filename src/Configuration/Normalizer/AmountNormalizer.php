<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AmountNormalizer implements NormalizerInterface
{
    public function __construct(private readonly NormalizerInterface $inner)
    {
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param string[] $context
     * @return array<string, mixed>
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $array = $this->inner->normalize($object, $format, $context);
        if (isset($array['amount'])) {
            $array['amount'] = round((float) $array['amount'], 2);
        }

        return $array;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof AmountNormalizerAwareInterface;
    }
}
