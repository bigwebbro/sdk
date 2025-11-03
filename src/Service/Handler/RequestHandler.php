<?php

declare(strict_types=1);

namespace Tiyn\MerchantApiSdk\Service\Handler;

use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tiyn\MerchantApiSdk\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Model\RequestModelInterface;

final class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(RequestModelInterface $command): string
    {
        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage();
            }
            throw new ValidationException(implode(', ', $messages));
        }

        return $this->serializer->serialize($command, 'json', [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true
        ]);
    }
}
