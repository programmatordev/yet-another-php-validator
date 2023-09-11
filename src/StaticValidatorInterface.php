<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

interface StaticValidatorInterface
{
    public static function choice(
        array $constraints,
        bool $multiple = false,
        ?int $minConstraint = null,
        ?int $maxConstraint = null,
        string $message = 'The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
        string $multipleMessage = 'The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
        string $minMessage = 'The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.',
        string $maxMessage = 'The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.'
    ): ChainedValidatorInterface;

    public static function country(
        string $code = 'alpha-2',
        string $message = 'The "{{ name }}" value is not a valid "{{ code }}" country code, "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function eachValue(
        Validator $validator,
        string $message = 'At key "{{ key }}": {{ message }}'
    ): ChainedValidatorInterface;

    public static function greaterThan(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function greaterThanOrEqual(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be greater than or equal to "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function lessThan(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be less than "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function lessThanOrEqual(
        mixed $constraint,
        string $message = 'The "{{ name }}" value should be less than or equal to "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function notBlank(
        ?callable $normalizer = null,
        string $message = 'The "{{ name }}" value should not be blank, "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function range(
        mixed $minConstraint,
        mixed $maxConstraint,
        string $message = 'The "{{ name }}" value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function rule(RuleInterface $constraint): ChainedValidatorInterface;

    public static function timezone(
        int $timezoneGroup = \DateTimeZone::ALL,
        ?string $countryCode = null,
        string $message = 'The "{{ name }}" value is not a valid timezone, "{{ value }}" given.'
    ): ChainedValidatorInterface;

    public static function type(
        string|array $constraint,
        string $message = 'The "{{ name }}" value should be of type "{{ constraint }}", "{{ value }}" given.'
    ): ChainedValidatorInterface;
}