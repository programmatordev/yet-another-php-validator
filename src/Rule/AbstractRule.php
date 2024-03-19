<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\ValidationException;

abstract class AbstractRule
{
    public function validate($value): bool
    {
        try {
            $this->assert($value);
        }
        catch (ValidationException) {
            return false;
        }

        return true;
    }

    /**
     * @throws ValidationException
     */
    public abstract function assert(mixed $value, ?string $name = null): void;
}