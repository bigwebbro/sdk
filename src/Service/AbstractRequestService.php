<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service;

class AbstractRequestService extends AbstractService
{
    public function __construct(
        protected string $secretPhrase,
        protected string $apiKey,
    ) {
        parent::__construct($secretPhrase);
    }

    protected function getEndpoint(string $format = '%s', string ...$slugs): string
    {
        return \sprintf($format, ...$slugs);
    }
}
