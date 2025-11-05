<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice\Enum;

enum DeliveryMethodEnum: string
{
    case EMAIL = 'EMAIL';
    case SMS = 'SMS';
    case URL = 'URL';

    public static function default(): self
    {
        return self::URL;
    }
}
