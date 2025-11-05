<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Sign;

final class Sign
{
    public static function hash(string $body, string $secretPhrase): string
    {
        return base64_encode(hash_hmac('sha512', $body, $secretPhrase, true));
    }
}
