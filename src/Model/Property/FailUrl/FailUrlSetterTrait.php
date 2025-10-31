<?php

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

/**
 * @property string $failUrl
 */
trait FailUrlSetterTrait
{
    public function setFailUrl(string $failUrl): static
    {
        $this->failUrl = $failUrl;

        return $this;
    }
}
