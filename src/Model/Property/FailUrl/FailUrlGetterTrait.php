<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\FailUrl;

/**
 * @property string $failUrl
 */
trait FailUrlGetterTrait
{
    public function getFailUrl(): string
    {
        return $this->failUrl;
    }
}
