<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\IsNullException;

class IsNull extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value should be null, {{ value }} given.';

    public function __construct(
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_null($value)) {
            throw new IsNullException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}