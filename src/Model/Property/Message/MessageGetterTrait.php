<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Message;

/**
 * @property string $message
 */
trait MessageGetterTrait
{
    public function getMessage(): string
    {
        return $this->message;
    }
}
