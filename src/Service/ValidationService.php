<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(
        public readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @param object $dto
     * @return array
     */
    public function validate(object $dto): array
    {
        $errors = $this->validator->validate($dto);
        $errorMessages = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $errorMessages[] = sprintf(
                '%s: %s',
                $error->getPropertyPath(),
                $error->getMessage(),
            );
        }

        return $errorMessages;
    }
}