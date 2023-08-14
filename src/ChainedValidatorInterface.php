<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

interface ChainedValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, string $name): void;

    public function validate(mixed $value): bool;

    // --- Rules ---

    public function all(
        array $constraints,
        string $message = 'At "{{ key }}": {{ message }}'
    ): ChainedValidatorInterface;

    public function choice(
        array $constraints,
        bool $multiple = false,
        ?int $minConstraint = null,
        ?int $maxConstraint = null,
        string $message = 'The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
        string $multipleMessage = 'The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
        string $minMessage = 'The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.',
        string $maxMessage = 'The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.'
    ): ChainedValidatorInterface;

    public function greaterThan(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function greaterThanOrEqual(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be greater than or equal to "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function lessThan(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be less than "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function lessThanOrEqual(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be less than or equal to "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function notBlank(
        ?callable $normalizer = null,
        string $message = 'The "{{ name }}" value should not be blank, "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function range(
        mixed $minConstraint,
        mixed $maxConstraint,
        string $message = 'The "{{ name }}" value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public function rule(RuleInterface $constraint): ChainedValidatorInterface;

    public function type(
        string|array $constraints,
        string $message = 'The "{{ name }}" value should be of type "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;
}