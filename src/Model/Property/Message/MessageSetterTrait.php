<?php

namespace Tiyn\MerchantApiSdk\Model\Property\Message;

/**
 * @property string $message
 */
trait MessageSetterTrait
{
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
