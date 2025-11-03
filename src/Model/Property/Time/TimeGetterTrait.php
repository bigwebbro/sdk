<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Time;

/**
 * @property \DateTimeImmutable $time
 */
trait TimeGetterTrait
{
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }
}
