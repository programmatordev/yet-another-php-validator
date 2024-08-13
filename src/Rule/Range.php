<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\RangeException;
use ProgrammatorDev\Validator\Exception\UnexpectedComparableException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Rule\Util\ComparableTrait;
use ProgrammatorDev\Validator\Validator;

class Range extends AbstractRule implements RuleInterface
{
    use ComparableTrait;

    private string $message = 'The {{ name }} value should be between {{ min }} and {{ max }}.';

    public function __construct(
        private readonly mixed $min,
        private readonly mixed $max,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!$this->isComparable($this->min, $this->max)) {
            throw new UnexpectedComparableException(
                get_debug_type($this->min),
                get_debug_type($this->max)
            );
        }

        if (!Validator::greaterThan($this->min)->validate($this->max)) {
            throw new UnexpectedValueException('Maximum value must be greater than minimum value.');
        }

        if (!Validator::greaterThanOrEqual($this->min)->lessThanOrEqual($this->max)->validate($value)) {
            throw new RangeException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'min' => $this->min,
                    'max' => $this->max
                ]
            );
        }
    }
}