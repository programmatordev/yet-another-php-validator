<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\NotNullException;
use ProgrammatorDev\Validator\Validator;

class NotNull extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value should not be null.';

    public function __construct(
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (Validator::isNull()->validate($value) === true) {
            throw new NotNullException(
                message: $this->message,
                parameters: [
                    'name' => $name
                ]
            );
        }
    }
}