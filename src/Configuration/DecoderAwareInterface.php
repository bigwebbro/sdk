<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration;

use Symfony\Component\Serializer\Encoder\DecoderInterface;

interface DecoderAwareInterface
{
    public function setDecoder(DecoderInterface $decoder): void;
}
