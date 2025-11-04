<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

/**
 * @property null|string $failUrl
 */
trait FailUrlSetterTrait
{
    public function setFailUrl(?string $failUrl): static
    {
        $this->failUrl = $failUrl;

        return $this;
    }
}
