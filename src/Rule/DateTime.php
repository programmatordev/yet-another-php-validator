<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\DateTimeException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class DateTime extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value is not a valid datetime.';

    public function __construct(
        private readonly string $format = 'Y-m-d H:i:s',
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedTypeException($value, 'string|\Stringable');
        }

        $value = (string) $value;

        \DateTimeImmutable::createFromFormat($this->format, $value);

        $errors = \DateTimeImmutable::getLastErrors();

        if ($errors !== false && ($errors['error_count'] > 0 || $errors['warning_count'] > 0)) {
            throw new DateTimeException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'value' => $value,
                    'format' => $this->format
                ]
            );
        }
    }
}