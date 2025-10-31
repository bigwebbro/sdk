<?php

namespace Tiyn\MerchantApiSdk\Model\Property\ExternalId;

/**
 * @property string $externalId
 */
trait ExternalIdGetterTrait
{
    public function getExternalId(): string
    {
        return $this->externalId;
    }
}