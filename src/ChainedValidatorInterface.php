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

    public function notBlank(array $options = []): ChainedValidatorInterface;

    public function greaterThan(mixed $constraint, array $options = []): ChainedValidatorInterface;

    public function greaterThanOrEqual(mixed $constraint, array $options = []): ChainedValidatorInterface;

    public function lessThan(mixed $constraint, array $options = []): ChainedValidatorInterface;

    public function choice(array $constraints, bool $multiple = false, ?int $minConstraint = null, ?int $maxConstraint = null, array $options = []): ChainedValidatorInterface;

    public function range(mixed $minConstraint, mixed $maxConstraint, array $options = []): ChainedValidatorInterface;
}