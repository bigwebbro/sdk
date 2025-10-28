<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client;

use Psr\Http\Client\ClientInterface;

interface HttpClientBuilderInterface
{
    public function setBaseUri(string $baseUrl): self;

    public function setTimeout(int $timeout): self;

    public function setApiKey(string $apiKey): self;

    public function setOptions(array $options): self;

    public function build(): ClientInterface;
}
