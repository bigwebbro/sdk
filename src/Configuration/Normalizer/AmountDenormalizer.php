<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AmountDenormalizer implements DenormalizerInterface
{
    public function __construct(private DenormalizerInterface $inner)
    {
    }

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
        if (isset($data['amount'])) {
            $data['amount'] = (string) $data['amount'];
        }

        if (isset($data['finalAmount'])) {
            $data['finalAmount'] = (string) $data['finalAmount'];
        }

        return $this->inner->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        return is_a($type, AmountDenormalizerAwareInterface::class, true) && (isset($data['amount']) || isset($data['finalAmount']));
    }
}