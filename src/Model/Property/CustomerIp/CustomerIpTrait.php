<?php

namespace Tiyn\MerchantApiSdk\Model\Property\CustomerIp;

use Symfony\Component\Validator\Constraints as Assert;

trait CustomerIpTrait
{
    #[Assert\Ip]
    private string $customerIp;
}