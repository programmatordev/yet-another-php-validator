<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ComparableTrait;

abstract class AbstractComparisonRule extends AbstractRule
{
    use ComparableTrait;

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!$this->isComparable($value, $this->constraint)) {
            throw new UnexpectedComparableException(
                get_debug_type($value),
                get_debug_type($this->constraint)
            );
        }

        if (!$this->compareValues($value, $this->constraint)) {
            throw new ($this->getException())(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraint' => $this->constraint
                ]
            );
        }
    }

    protected abstract function compareValues(mixed $value1, mixed $value2): bool;

    protected abstract function getException(): string;
}