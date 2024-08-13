<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\LocaleException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Intl\Locales;

class Locale extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value is not a valid locale.';

    public function __construct(
        private readonly bool $canonicalize = false,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        // keep original value for parameters
        $input = $value;

        if ($this->canonicalize) {
            $input = \Locale::canonicalize($input);
        }

        if (!Locales::exists($input)) {
            throw new LocaleException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'value' => $value
                ]
            );
        }
    }
}