<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

interface ClientBuilderInterface
{
    public function setBaseUri(string $baseUrl): self;

    public function setTimeout(int $timeout): self;

    public function setApiKey(string $apiKey): self;

    /**
     * @param array<string,mixed> $options
     */
    public function setOptions(array $options): self;

    public function addDecorator(ClientInterface $decorator): self;

    public function build(): ClientInterface;
}
