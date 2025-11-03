<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Model\Invoice;

use Tiyn\MerchantApiSdk\Configuration\Serializer\Denormalizer\AmountDenormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Configuration\Serializer\Normalizer\AmountNormalizerAwareInterface;
use Tiyn\MerchantApiSdk\Model\Invoice\Payment\Payment;
use Tiyn\MerchantApiSdk\Model\Property\Amount\AmountGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Amount\AmountTrait;
use Tiyn\MerchantApiSdk\Model\Property\Currency\CurrencyGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Currency\CurrencyTrait;
use Tiyn\MerchantApiSdk\Model\Property\CustomData\CustomDataGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\CustomData\CustomDataTrait;
use Tiyn\MerchantApiSdk\Model\Property\CustomerPhone\CustomerPhoneGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\CustomerPhone\CustomerPhoneTrait;
use Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod\DeliveryMethodGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\DeliveryMethod\DeliveryMethodTrait;
use Tiyn\MerchantApiSdk\Model\Property\Description\DescriptionGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Description\DescriptionTrait;
use Tiyn\MerchantApiSdk\Model\Property\Email\CustomerEmailGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Email\CustomerEmailTrait;
use Tiyn\MerchantApiSdk\Model\Property\ExpirationDate\ExpirationDateGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\ExpirationDate\ExpirationDateTrait;
use Tiyn\MerchantApiSdk\Model\Property\ExternalId\ExternalIdGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\ExternalId\ExternalIdTrait;
use Tiyn\MerchantApiSdk\Model\Property\FailUrl\FailUrlGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\FailUrl\FailUrlTrait;
use Tiyn\MerchantApiSdk\Model\Property\FinalAmount\FinalAmountGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\FinalAmount\FinalAmountTrait;
use Tiyn\MerchantApiSdk\Model\Property\OfdData\OfdDataGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\OfdData\OfdDataTrait;
use Tiyn\MerchantApiSdk\Model\Property\SuccessUrl\SuccessUrlGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\SuccessUrl\SuccessUrlTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidGetterTrait;
use Tiyn\MerchantApiSdk\Model\Property\Uuid\UuidTrait;

final class GetInvoiceResponse implements AmountDenormalizerAwareInterface, AmountNormalizerAwareInterface
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
     * @var Payment[]
     */
    private array $payments = [];

    /**
     * @var Status
     * @phpstan-ignore-next-line
     */
    private Status $status;

    /**
     * @return Payment[]
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
