<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface MerchantApiSdkBuilderInterface
{
    public function setClient(ClientInterface $client): self;

    public function setSecretPhrase(string $secretPhrase): self;

    public function setApiKey(string $apiKey): self;

    public function setSerializer(SerializerInterface $serializer): self;

    public function setValidator(ValidatorInterface $validator): self;

    public function build(): MerchantApiSdkInterface;
}
