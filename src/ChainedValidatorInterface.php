<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

interface ChainedValidatorInterface
{
    // --- Common ---

    public function validate(mixed $input): bool;

    /**
     * @throws ValidationException
     */
    public function assert(mixed $input, string $name): void;

    // --- Rules ---

//    public function notBlank(): ChainedValidatorInterface;
//
//    public function greaterThan(mixed $constraint): ChainedValidatorInterface;
}