<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\IsTrueException;

class IsTrue extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value should be true, {{ value }} given.';

    public function __construct(
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($value !== true) {
            throw new IsTrueException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}