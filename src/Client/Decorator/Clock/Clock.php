<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator\Clock;

final class Clock implements ClockInterface
{
    private ?float $timestamp = null;

    public function start(): void
    {
        $this->timestamp = microtime(true);
    }

    public function durationAsStringMs(): string
    {
        if (null !== $this->timestamp) {
            $timestamp = $this->timestamp;
            $this->timestamp = null;

            return \sprintf('%d ms', round((microtime(true) - $timestamp) * 1000, 2));
        }

        return '';
    }
}
