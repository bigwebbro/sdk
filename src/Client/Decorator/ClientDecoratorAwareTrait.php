<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client\Decorator;

use Psr\Http\Client\ClientInterface;

trait ClientDecoratorAwareTrait
{
    private ClientInterface $inner;

    public function withClient(ClientInterface $inner): self|ClientInterface
    {
        $this->inner = $inner;

        return $this;
    }
}
