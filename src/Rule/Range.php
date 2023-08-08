<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RangeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ComparableTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class Range extends AbstractRule implements RuleInterface
{
    use ComparableTrait;

    private string $message;

    public function __construct(
        private readonly mixed $minConstraint,
        private readonly mixed $maxConstraint,
        ?string $message = null
    )
    {
        $this->message = $message ?? 'The "{{ name }}" value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.';
    }

    public function assert(mixed $value, string $name): void
    {
        if (!$this->isComparable($this->minConstraint, $this->maxConstraint)) {
            throw new UnexpectedComparableException(
                get_debug_type($this->minConstraint),
                get_debug_type($this->maxConstraint)
            );
        }

        if (
            !Validator::greaterThan($this->minConstraint)
                ->validate($this->maxConstraint)
        ) {
            throw new UnexpectedValueException(
                'Max constraint value must be greater than min constraint value.'
            );
        }

        if (
            !Validator::greaterThanOrEqual($this->minConstraint)
                ->lessThanOrEqual($this->maxConstraint)
                ->validate($value)
        ) {
            throw new RangeException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'minConstraint' => $this->minConstraint,
                    'maxConstraint' => $this->maxConstraint
                ]
            );
        }
    }
}