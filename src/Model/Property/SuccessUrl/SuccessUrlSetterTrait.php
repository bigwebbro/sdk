<?php

namespace Tiyn\MerchantApiSdk\Model\Property\SuccessUrl;

/**
 * @property string $successUrl
 */
trait SuccessUrlSetterTrait
{
    public function setSuccessUrl(string $successUrl): static
    {
        $this->successUrl = $successUrl;

        return $this;
    }
}
