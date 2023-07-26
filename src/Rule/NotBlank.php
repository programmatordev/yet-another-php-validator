<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    private string $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message ?? 'The {{ name }} value should not be blank.';
    }

    /**
     * @throws NotBlankException
     */
    public function validate(mixed $input): void
    {
        // Strip whitespace in case of a string
        if (\is_string($input)) {
            $input = trim($input);
        }

        // Does not allow null, false, [] and ''
        if ($input === false || (empty($input) && $input != '0')) {
            throw new NotBlankException(
                message: $this->message,
                parameters: [
                    'input' => $input,
                    'name' => $this->getName()
                ]
            );
        }
    }
}