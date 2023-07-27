<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

interface ChainedValidatorInterface
{
    // --- Common ---

    public function validate(mixed $value): bool;

    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, string $name): void;

    // --- Rules ---

    public function notBlank(?string $message = null): ChainedValidatorInterface;

//    public function greaterThan(mixed $constraint): ChainedValidatorInterface;
}