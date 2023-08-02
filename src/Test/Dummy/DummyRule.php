<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Dummy;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class DummyRule implements RuleInterface
{
    public function assert(mixed $value, string $name): void
    {
        if (!$value) {
            throw new ValidationException(
                message: 'Dummy exception.'
            );
        }
    }

    public function validate(mixed $value): bool
    {
        return (bool) $value;
    }

}