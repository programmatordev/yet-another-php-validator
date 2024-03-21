<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\NotBlankException;

class NotBlank extends AbstractRule implements RuleInterface
{
    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value should not be blank, {{ value }} given.';

    public function __construct(
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer = $normalizer;
        $this->message = $message ?? $this->message;
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
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