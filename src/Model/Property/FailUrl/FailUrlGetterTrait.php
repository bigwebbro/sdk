<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

/**
 * @property null|string $failUrl
 */
trait FailUrlGetterTrait
{
    public function getFailUrl(): ?string
    {
        return $this->failUrl;
    }
}
