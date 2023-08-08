<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    // Using array to bypass unallowed callable type in properties
    private array $normalizer;

    private string $message;

    public function __construct(
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer['callable'] = $normalizer;
        $this->message = $message ?? 'The "{{ name }}" value should not be blank, "{{ value }}" given.';
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, string $name): void
    {
        // Keep original value for parameter
        $input = $value;

        // Call normalizer if provided
        if ($this->normalizer['callable'] !== null) {
            $input = ($this->normalizer['callable'])($input);
        }

        // Do not allow null, false, [] and ''
        if ($input === false || (empty($input) && $input != '0')) {
            throw new NotBlankException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}