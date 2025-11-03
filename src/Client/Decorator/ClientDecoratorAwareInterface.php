<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator;

use Psr\Http\Client\ClientInterface;

interface ClientDecoratorAwareInterface
{
    public function withClient(ClientInterface $inner): self|ClientInterface;
}
