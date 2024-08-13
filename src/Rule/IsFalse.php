<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\IsFalseException;

class IsFalse extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value should be false.';

    public function __construct(
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($value !== false) {
            throw new IsFalseException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}