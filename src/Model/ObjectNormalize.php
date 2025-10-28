<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model;

interface ObjectNormalize
{
    public function toArray(): array;
}
