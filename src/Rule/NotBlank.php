<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    private string $message;

    public function __construct(string $message = null)
    {
        $this->message = $message ?? 'The "{{ name }}" value should not be blank, "{{ value }}" given.';
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, string $name): void
    {
        // Do not allow null, false, [] and ''
        if ($value === false || (empty($value) && $value != '0')) {
            throw new NotBlankException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'value' => $value
                ]
            );
        }
    }
}