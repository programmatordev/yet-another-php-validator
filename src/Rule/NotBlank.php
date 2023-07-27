<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    public function __construct(private ?string $message = null)
    {
        $this->message ??= 'The "{{ name }}" value should not be blank, "{{ value }}" given.';
    }

    /**
     * @throws NotBlankException
     */
    public function validate(mixed $value): void
    {
        // Keep value unchanged for parameters
        $input = $value;

        // Do not allow null, false, [] and ''
        if ($input === false || (empty($input) && $input != '0')) {
            throw new NotBlankException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $this->getName()
                ]
            );
        }
    }
}