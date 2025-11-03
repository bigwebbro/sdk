<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\RequestId;

/**
 * @property string $requestId
 */
trait RequestIdGetterTrait
{
    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
