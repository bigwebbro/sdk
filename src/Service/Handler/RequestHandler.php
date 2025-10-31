<?php

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Exception\Validation\JsonProcessingException;
use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\ToArrayInterface;

class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(ToArrayInterface $command): string
    {
        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage();
            }
            throw new ValidationException(implode(', ', $messages));
        }

        try {
            $json = json_encode($command->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\JsonException $e) {
            throw new JsonProcessingException($e->getMessage(), $e->getCode(), $e);
        }

        return $json;
    }
}