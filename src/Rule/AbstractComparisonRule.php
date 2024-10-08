<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\UnexpectedComparableException;
use ProgrammatorDev\Validator\Rule\Util\ComparableTrait;

abstract class AbstractComparisonRule extends AbstractRule
{
    use ComparableTrait;

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!$this->isComparable($value, $this->constraint)) {
            throw new UnexpectedComparableException($value, $this->constraint);
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