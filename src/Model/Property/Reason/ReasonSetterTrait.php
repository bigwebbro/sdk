<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Reason;

/**
 * @property string $reason
 */
trait ReasonSetterTrait
{
    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }
}
