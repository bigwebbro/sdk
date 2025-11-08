<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Account;

/**
 * @property null|string $account
 */
trait AccountSetterTrait
{
    public function setAccount(?string $account): static
    {
        $this->account = $account;

        return $this;
    }
}
