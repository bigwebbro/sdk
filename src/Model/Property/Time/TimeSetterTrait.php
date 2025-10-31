<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Time;

/**
 * @property \DateTimeImmutable $time
 */
trait TimeSetterTrait
{
    public function setTime(\DateTimeImmutable $time): static
    {
        $this->time = $time;

        return $this;
    }
}
