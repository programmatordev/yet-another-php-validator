<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\ValidationException;

interface RuleInterface
{
    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, ?string $name = null): void;

    public function validate(mixed $value): bool;
}