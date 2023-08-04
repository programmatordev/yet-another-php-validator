<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ComparableTrait;

abstract class AbstractComparisonRule extends AbstractRule
{
    use ComparableTrait;

    public function assert(mixed $value, string $name): void
    {
        $constraint = $this->convertToComparable($this->constraint);
        $value = $this->convertToComparable($value);

        if (!$this->isComparable($constraint, $value)) {
            throw new UnexpectedComparableException(
                get_debug_type($constraint),
                get_debug_type($value)
            );
        }

        if (!$this->comparison($constraint, $value)) {
            throw new ($this->getException())(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraint' => $constraint
                ]
            );
        }
    }

    protected abstract function comparison(mixed $constraint, mixed $value): bool;

    protected abstract function getException(): string;
}