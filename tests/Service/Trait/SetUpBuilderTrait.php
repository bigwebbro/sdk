<?php

declare(strict_types=1);

namespace Tests\Service\Trait;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Tiyn\MerchantApiSdk\Client\ClientBuilderInterface;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;
use Tiyn\MerchantApiSdk\Client\Util\Clock\Clock;
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
            ->setApiKey('test-api-key')
            ->addDecorator(new ClientLoggingDecorator($logger, new Clock()))
        ;

        $this->sdkBuilder = (new MerchantApiSdkBuilder())
            ->setSecretPhrase('test-secret-phrase')
        ;
    }
}
