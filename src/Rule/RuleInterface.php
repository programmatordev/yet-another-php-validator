<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

interface RuleInterface
{
    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, ?string $name = null): void;

    public function validate(mixed $value): bool;
}