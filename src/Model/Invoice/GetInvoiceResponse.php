<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{Amount\AmountGetterTrait,
    Amount\AmountTrait,
    Currency\CurrencyGetterTrait,
    Currency\CurrencyTrait,
    CustomData\CustomDataGetterTrait,
    CustomData\CustomDataTrait,
    CustomerPhone\CustomerPhoneGetterTrait,
    CustomerPhone\CustomerPhoneTrait,
    DeliveryMethod\DeliveryMethodGetterTrait,
    DeliveryMethod\DeliveryMethodTrait,
    Description\DescriptionGetterTrait,
    Description\DescriptionTrait,
    Email\CustomerEmailGetterTrait,
    Email\CustomerEmailTrait,
    ExpirationDate\ExpirationDateGetterTrait,
    ExpirationDate\ExpirationDateTrait,
    ExternalId\ExternalIdGetterTrait,
    ExternalId\ExternalIdTrait,
    FailUrl\FailUrlGetterTrait,
    FailUrl\FailUrlTrait,
    FinalAmount\FinalAmountGetterTrait,
    FinalAmount\FinalAmountTrait,
    OfdData\OfdDataGetterTrait,
    OfdData\OfdDataTrait,
    SuccessUrl\SuccessUrlGetterTrait,
    SuccessUrl\SuccessUrlTrait,
    Uuid\UuidGetterTrait,
    Uuid\UuidTrait};
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\AmountDenormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Serializer\Normalizer\AmountAwareNormalizationInterface;

final class GetInvoiceResponse implements
    AmountDenormalizerAwareInterface,
    AmountAwareNormalizationInterface
{
    use UuidTrait;
    use UuidGetterTrait;

    use ExternalIdTrait;
    use ExternalIdGetterTrait;

    use AmountTrait;
    use AmountGetterTrait;

    use FinalAmountTrait;
    use FinalAmountGetterTrait;

    use CurrencyTrait;
    use CurrencyGetterTrait;

    use DescriptionTrait;
    use DescriptionGetterTrait;

    use CustomerPhoneTrait;
    use CustomerPhoneGetterTrait;

    use CustomerEmailTrait;
    use CustomerEmailGetterTrait;

    use CustomDataTrait;
    use CustomDataGetterTrait;

    use SuccessUrlTrait;
    use SuccessUrlGetterTrait;

    use FailUrlTrait;
    use FailUrlGetterTrait;

    use DeliveryMethodTrait;
    use DeliveryMethodGetterTrait;

    use ExpirationDateTrait;
    use ExpirationDateGetterTrait;

    use OfdDataTrait;
    use OfdDataGetterTrait;

    /**
     * @var list<\Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment>
     */
    private array $payments = [];

    /**
     * @var Status
     * @phpstan-ignore-next-line
     */
    private Status $status;

    /**
     * @return list<\Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment>
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
