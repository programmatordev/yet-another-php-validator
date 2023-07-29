<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

interface ChainedValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, string $name): void;

    public function validate(mixed $value): bool;

    // --- Rules ---

    public function notBlank(string $message = null): ChainedValidatorInterface;

    public function greaterThan(mixed $constraint, string $message = null): ChainedValidatorInterface;
}