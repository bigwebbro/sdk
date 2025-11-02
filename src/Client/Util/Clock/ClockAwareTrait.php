<?php

namespace Tiyn\MerchantApiSdk\Client\Util\Clock;

trait ClockAwareTrait
{
    private ClockInterface $clock;

    public function setClock(ClockInterface $clock): void
    {
        $this->clock = $clock;
    }
}