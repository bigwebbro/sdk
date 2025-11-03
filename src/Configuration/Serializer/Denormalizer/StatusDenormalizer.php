<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Status;

final class StatusDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_FLAG = '__status_denormalizer_running';

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::CONTEXT_FLAG] = true;

        if (isset($data['status'])) {
            $data['status'] = $this->denormalizer->denormalize($data['status'], Status::class);
        }
        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        $args = \func_get_args();
        $context = $args[3] ?? [];

        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return isset($data['status']);
    }
}
