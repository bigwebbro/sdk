<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\SuccessUrl;

/**
 * @property null|string $successUrl
 */
trait SuccessUrlGetterTrait
{
    public function getSuccessUrl(): ?string
    {
        return $this->successUrl;
    }
}
