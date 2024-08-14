<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\BlankException;

class Blank extends AbstractRule implements RuleInterface
{
    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value should be blank.';

    public function __construct(
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer = $normalizer;
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
        }

        if ($value !== null && $value !== false && $value !== '' && $value !== []) {
            throw new BlankException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}