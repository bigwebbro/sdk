<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Model\Property\{Amount\AmountSetterTrait,
    Amount\AmountTrait,
    Currency\CurrencySetterTrait,
    Currency\CurrencyTrait,
    CustomData\CustomDataSetterTrait,
    CustomData\CustomDataTrait,
    CustomerIp\CustomerIpTrait,
    CustomerPhone\CustomerPhoneSetterTrait,
    CustomerPhone\CustomerPhoneTrait,
    DeliveryMethod\DeliveryMethodSetterTrait,
    DeliveryMethod\DeliveryMethodTrait,
    Description\DescriptionSetterTrait,
    Description\DescriptionTrait,
    Email\CustomerEmailSetterTrait,
    Email\CustomerEmailTrait,
    ExpirationDate\ExpirationDateSetterTrait,
    ExpirationDate\ExpirationDateTrait,
    ExternalId\ExternalIdSetterTrait,
    ExternalId\ExternalIdTrait,
    FailUrl\FailUrlSetterTrait,
    FailUrl\FailUrlTrait,
    OfdData\OfdDataSetterTrait,
    OfdData\OfdDataTrait,
    SuccessUrl\SuccessUrlSetterTrait,
    SuccessUrl\SuccessUrlTrait};
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;
use Tiyn\MerchantApiSdk\Serializer\Denormalizer\AmountDenormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Serializer\Normalizer\AmountAwareNormalizationInterface;

final class CreateInvoiceRequest implements
    RequestModelInterface,
    AmountAwareNormalizationInterface,
    AmountDenormalizerAwareInterface
{
    use ExternalIdTrait;
    use ExternalIdSetterTrait;

    use AmountTrait;
    use AmountSetterTrait;

    use CurrencyTrait;
    use CurrencySetterTrait;

    use DescriptionTrait;
    use DescriptionSetterTrait;

    use CustomerPhoneTrait;
    use CustomerPhoneSetterTrait;

    use CustomerEmailTrait;
    use CustomerEmailSetterTrait;

    use CustomDataTrait;
    use CustomDataSetterTrait;

    use SuccessUrlTrait;
    use SuccessUrlSetterTrait;

    use FailUrlTrait;
    use FailUrlSetterTrait;

    use DeliveryMethodTrait;
    use DeliveryMethodSetterTrait;

    use ExpirationDateTrait;
    use ExpirationDateSetterTrait;

    use OfdDataTrait;
    use OfdDataSetterTrait;

    use CustomerIpTrait;
    use CustomDataSetterTrait;
}
