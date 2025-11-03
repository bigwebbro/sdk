<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{
    Message\MessageGetterTrait,
    Message\MessageTrait,
    Name\NameGetterTrait,
    Name\NameTrait,
    Time\TimeGetterTrait,
    Time\TimeTrait
};

final class Status
{
    use NameTrait;
    use NameGetterTrait;
    use MessageTrait;
    use MessageGetterTrait;
    use TimeTrait;
    use TimeGetterTrait;
}
