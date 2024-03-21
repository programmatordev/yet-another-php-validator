<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\GreaterThanOrEqualException;

class GreaterThanOrEqual extends AbstractComparisonRule implements RuleInterface
{
    protected string $message = 'The {{ name }} value should be greater than or equal to {{ constraint }}, {{ value }} given.';

    public function __construct(
        protected readonly mixed $constraint,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    protected function compareValues(mixed $value1, mixed $value2): bool
    {
        if (\is_string($value1) && \is_string($value2)) {
            return strcmp($value1, $value2) >= 0;
        }

        return $value1 >= $value2;
    }

    protected function getException(): string
    {
        return GreaterThanOrEqualException::class;
    }
}