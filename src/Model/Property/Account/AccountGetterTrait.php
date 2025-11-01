<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Account;

/**
 * @property string $account
 */
trait AccountGetterTrait
{
    public function getAccount(): string
    {
        return $this->account;
    }
}
