<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    // Using array to bypass unallowed callable type in properties
    private array $normalizer;
    private string $message = 'The {{ name }} value should not be blank, {{ value }} given.';

    public function __construct(
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer['callable'] = $normalizer;
        $this->message = $message ?? $this->message;
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->normalizer['callable'] !== null) {
            $value = ($this->normalizer['callable'])($value);
        }

        // Do not allow null, false, [] and ''
        if ($value === false || (empty($value) && $value != '0')) {
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