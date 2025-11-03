<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Reason;

/**
 * @property string $reason
 */
trait ReasonGetterTrait
{
    public function getReason(): string
    {
        return $this->reason;
    }
}
