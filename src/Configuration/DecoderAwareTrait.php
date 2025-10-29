<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Configuration;

use Symfony\Component\Serializer\Encoder\DecoderInterface;

trait DecoderAwareTrait
{
    protected DecoderInterface $decoder;

    public function setDecoder(DecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }
}
