<?php

namespace Tiyn\MerchantApiSdk\Configuration\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Status;

class StatusDenormalizer implements DenormalizerInterface
{
    public function __construct(private NormalizerInterface $normalizer) {}

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (isset($data['status'])) {
            $data['status'] = $this->denormalize($data['status'], Status::class);
        }
        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return isset($data['status']);
    }
}
