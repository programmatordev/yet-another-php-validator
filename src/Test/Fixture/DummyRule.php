<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Fixture;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\AbstractRule;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

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