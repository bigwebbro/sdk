<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{Message\MessageGetterTrait,
    Message\MessageTrait,
    Time\TimeGetterTrait,
    Time\TimeTrait};
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\InvoiceStatusEnum;

final class Status
{
    use MessageTrait;
    use MessageGetterTrait;

    use TimeTrait;
    use TimeGetterTrait;

    /**
     * @phpstan-ignore-next-line property.onlyRead property.uninitializedReadonly
     */
    private InvoiceStatusEnum $name;

    public function getStatus(): InvoiceStatusEnum
    {
        return $this->name;
    }
}
