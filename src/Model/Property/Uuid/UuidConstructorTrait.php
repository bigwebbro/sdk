<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Property\Uuid;

trait UuidConstructorTrait
{
    public function __construct(
        private readonly string $uuid,
    ) {
    }
}
