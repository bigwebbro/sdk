<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

abstract class AbstractService
{
    public function __construct(
        protected string $secretPhrase,
    ) {
    }
}
