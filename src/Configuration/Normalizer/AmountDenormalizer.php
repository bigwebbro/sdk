<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AmountDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_FLAG = '__amount_denormalizer_running';

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

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return is_a($type, AmountDenormalizerAwareInterface::class, true)
            && (isset($data['amount']) || isset($data['finalAmount']));
    }
}
