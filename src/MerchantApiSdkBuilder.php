<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Validator\Validation;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientExceptionDecorator;
use Tiyn\MerchantApiSdk\Client\Decorator\HttpClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Guzzle\GuzzleHttpClientBuilder;
use Tiyn\MerchantApiSdk\Client\HttpClientBuilderInterface;
use Tiyn\MerchantApiSdk\Configuration\DecoderAwareInterface;
use Tiyn\MerchantApiSdk\Configuration\DecoderAwareTrait;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\AmountNormalizer;
use Tiyn\MerchantApiSdk\Configuration\Normalizer\DataTimeDenormalizer;
use Tiyn\MerchantApiSdk\Configuration\ValidatorAwareInterface;
use Tiyn\MerchantApiSdk\Configuration\ValidatorAwareTrait;
use Tiyn\MerchantApiSdk\Service\Handler\RequestHandler;
use Tiyn\MerchantApiSdk\Service\Handler\ResponseHandler;
use Tiyn\MerchantApiSdk\Service\InvoicesService;

final class MerchantApiSdkBuilder implements
    SerializerAwareInterface,
    DenormalizerAwareInterface,
    DecoderAwareInterface,
    LoggerAwareInterface,
    ValidatorAwareInterface
{
    use SerializerAwareTrait;
    use DenormalizerAwareTrait;
    use DecoderAwareTrait;
    use LoggerAwareTrait;
    use ValidatorAwareTrait;

    /**
     * @var class-string[]
     */
    private array $decorators = [];

    private ?HttpClientBuilderInterface $builder = null;

    private int $timeout = 5;

    private string $baseUri;

    private string $apiKey;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    private string $secretPhrase;

    /**
     * @param class-string $fqcn
     * @return $this
     */
    public function addHttpApiClientDecorator(string $fqcn): self
    {
        $this->decorators[] = $fqcn;

        return $this;
    }

    public function setHttpClientBuilder(HttpClientBuilderInterface $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     * @return $this
     */
    public function setClientOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setSecretPhrase(string $secretPhrase): self
    {
        $this->secretPhrase = $secretPhrase;

        return $this;
    }

    public function build(): MerchantApiSdk
    {
        if (null === $this->builder) {
            $this->builder = new GuzzleHttpClientBuilder();
        }

        $client = $this->builder
            ->setBaseUri($this->baseUri)
            ->setApiKey($this->apiKey)
            ->setTimeout($this->timeout)
            ->setOptions($this->options)
            ->build();

        $client = new HttpClientExceptionDecorator($client);

        if (!empty($this->decorators)) {
            foreach ($this->decorators as $decorator) {
                $client = new $decorator($client);

                if (HttpClientLoggingDecorator::class === $decorator) {
                    $client->setLogger($this->logger);
                }
            }
        }

        /** @phpstan-ignore isset.property */
        if (!isset($this->serializer)) {
            $extractor = new PropertyInfoExtractor(typeExtractors: [new PhpDocExtractor(), new ReflectionExtractor()]);
            $defaultNormalizer = new ObjectNormalizer(
                propertyTypeExtractor: $extractor
            );
            $amountDenormalize = new AmountDenormalizer($defaultNormalizer);
            $this->serializer = new Serializer(
                [
//                    new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s.uP']),
//                    $amountDenormalize,
//                    new AmountNormalizer($defaultNormalizer),
                    new ArrayDenormalizer(),
                    $defaultNormalizer,
                ],
                [new JsonEncoder()]
            );
//            $amountDenormalize->setDenormalizer($this->serializer);
//            $defaultNormalizer->setSerializer($this->serializer);
            $this->denormalizer = $this->serializer;
        }

//        if (!isset($this->denormalizer)) {
//            $denormalizer = new ObjectNormalizer();
//            $denormalizer->setSerializer($this->serializer);
//        }

        if (!isset($this->decoder)) {
            $this->decoder = new JsonDecode();
        }

        if (!isset($this->validator)) {
            $this->validator = Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator();
        }

        $invoiceService = new InvoicesService(
            $client,
            $this->denormalizer,
            new RequestHandler($this->validator, $this->serializer),
            new ResponseHandler($this->decoder, $this->denormalizer),
            $this->secretPhrase,
        );

        return new MerchantApiSdk($invoiceService);
    }
}
