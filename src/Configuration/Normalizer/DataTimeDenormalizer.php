<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

class DataTimeDenormalizer implements \Symfony\Component\Serializer\Normalizer\DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (isset($data['expirationData'])) {
            $data['expirationData'] = new \DateTimeImmutable($data['expirationData']);
        }

        return $data;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return is_a($type, DateTimeDenormalizerAwareInterface::class, true);
    }
}