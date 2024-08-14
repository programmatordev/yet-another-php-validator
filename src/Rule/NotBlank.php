<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\NotBlankException;
use ProgrammatorDev\Validator\Validator;

class NotBlank extends AbstractRule implements RuleInterface
{
    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value should not be blank.';

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
        if (Validator::blank(normalizer: $this->normalizer)->validate($value) === true) {
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