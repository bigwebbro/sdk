<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\SuccessUrl;

/**
 * @property null|string $successUrl
 */
trait SuccessUrlSetterTrait
{
    public function setSuccessUrl(?string $successUrl): static
    {
        $this->successUrl = $successUrl;

        return $this;
    }
}
