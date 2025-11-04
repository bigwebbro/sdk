<?php

declare(strict_types=1);

namespace Tests\Service\Trait;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;
use Tiyn\MerchantApiSdk\Client\Util\Clock\Clock;
use Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;
use Tiyn\MerchantApiSdk\MerchantApiSdkInterface;

trait SetUpSdkTrait
{
    private MerchantApiSdkInterface $sdk;

    protected function setUp(): void
    {
        $logger = new Logger('test-logger');
        $logger->pushHandler(new StreamHandler('php://stdout'));

        $client = (new ClientBuilder())
            ->setBaseUri('https://test')
            ->setTimeout(5)
            ->addDecorator(new ClientLoggingDecorator($logger, new Clock()))
            ->build()
        ;

        $this->sdk = (new MerchantApiSdkBuilder())
            ->setSecretPhrase('test-secret-phrase')
            ->setApiKey('test-api-key')
            ->setClient($client)
            ->build()
        ;
    }
}
