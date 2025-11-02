<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Tiyn\MerchantApiSdk\Service\InvoicesServiceInterface;

interface MerchantApiSdkInterface
{
    public function invoice(): InvoicesServiceInterface;
}
