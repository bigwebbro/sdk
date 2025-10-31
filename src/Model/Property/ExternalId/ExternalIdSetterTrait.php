<?php

namespace Tiyn\MerchantApiSdk\Model\Property\ExternalId;

/**
 * @property string $externalId
 */
trait ExternalIdSetterTrait
{
    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }
}