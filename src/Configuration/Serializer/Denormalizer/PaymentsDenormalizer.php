<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;

final class PaymentsDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_FLAG = '__payments_denormalizer_running';

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::CONTEXT_FLAG] = true;

        if ($data['payments'] && \is_array($data['payments'])) {
            $data['payments'] = $this->denormalizer->denormalize($data['payments'], Payment::class . '[]', $format, $context);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function supportsDenormalization($data, $type, $format = null, ...$args)
    {
        $context = $args[0] ?? [];

        if (!empty($context[self::CONTEXT_FLAG])) {
            return false;
        }

        return isset($data['payments']);
    }
}
