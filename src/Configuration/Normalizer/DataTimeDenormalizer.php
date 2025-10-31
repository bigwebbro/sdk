<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

class DataTimeDenormalizer implements \Symfony\Component\Serializer\Normalizer\DenormalizerInterface
{
    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array<string, mixed> $context
     * @return mixed
     * @throws \Exception
     */
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
