<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Exception\Transport;

use Psr\Http\Client\ClientExceptionInterface;

class ConnectionException extends \RuntimeException implements ClientExceptionInterface
{
}
