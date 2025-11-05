<?php

declare(strict_types=1);

namespace Tests\Service\Trait;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Tiyn\MerchantApiSdk\Client\ClientBuilderInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Decorator\Clock\Clock;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;
use Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;

trait SetUpBuilderTrait
{
    private ClientBuilderInterface $client;

    private MerchantApiSdkBuilder $sdkBuilder;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $this->client = (new ClientBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->addDecorator(new ClientLoggingDecorator($logger, new Clock()))
        ;

        $this->sdkBuilder = (new MerchantApiSdkBuilder())
            ->setApiKey('test-api-key')
            ->setSecretPhrase('test-secret-phrase')
        ;
    }
}
