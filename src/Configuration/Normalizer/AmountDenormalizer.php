<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AmountDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return mixed|string
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $context['__amount_denormalizer_running'] = true;

        if (isset($data['amount'])) {
            $data['amount'] = (string) $data['amount'];
        }

        if (isset($data['finalAmount'])) {
            $data['finalAmount'] = (string) $data['finalAmount'];
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        if (!empty($context['__amount_denormalizer_running'])) {
            return false;
        }

        return is_a($type, AmountDenormalizerAwareInterface::class, true) && (isset($data['amount']) || isset($data['finalAmount']));
    }
}