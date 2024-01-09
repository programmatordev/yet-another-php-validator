<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    // Using array to bypass unallowed callable type in properties
    private array $normalizer;

    public function __construct(
        ?callable $normalizer = null,
        private readonly string $message = 'The {{ name }} value should not be blank, {{ value }} given.'
    )
    {
        $this->normalizer['callable'] = $normalizer;
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