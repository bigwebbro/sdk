<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface MerchantApiSdkBuilderInterface
{
    public function setClient(ClientInterface $client): \Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;

    public function setSecretPhrase(string $secretPhrase): \Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;

    public function setSerializer(SerializerInterface $serializer): MerchantApiSdkBuilder;

    public function setValidator(ValidatorInterface $validator): MerchantApiSdkBuilder;

    public function build(): MerchantApiSdkInterface;
}
