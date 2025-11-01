<?php

declare(strict_types=1);

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
