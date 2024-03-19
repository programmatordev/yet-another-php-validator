<?php

namespace ProgrammatorDev\Validator\Test\Fixture;

use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Rule\AbstractRule;
use ProgrammatorDev\Validator\Rule\RuleInterface;

class DummyRule extends AbstractRule implements RuleInterface
{
    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_bool($value)) {
            throw new UnexpectedValueException('Dummy unexpected value.');
        }

        if ($value === false) {
            throw new ValidationException('Dummy exception.');
        }
    }
}