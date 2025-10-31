<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model;

interface ToArrayInterface
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array;
}
