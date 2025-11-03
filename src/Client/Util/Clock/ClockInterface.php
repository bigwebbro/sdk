<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Util\Clock;

interface ClockInterface
{
    public function start(): void;

    public function durationAsStringMs(): string;
}
