<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    public function __construct(private ?string $message = null)
    {
        $this->message ??= 'The {{ name }} value should not be blank.';
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

        // Do not allow null, false, [] and ''
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