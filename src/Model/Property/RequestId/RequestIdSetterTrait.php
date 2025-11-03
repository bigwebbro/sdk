<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\RequestId;

/**
 * @property string $requestId
 */
trait RequestIdSetterTrait
{
    public function setRequestId(string $requestId): static
    {
        $this->requestId = $requestId;

        return $this;
    }
}
