<?php

namespace ProgrammatorDev\Validator;

use ProgrammatorDev\Validator\Rule\RuleInterface;

interface StaticValidatorInterface
{
    public static function choice(
        array $constraints,
        bool $multiple = false,
        ?int $min = null,
        ?int $max = null,
        string $message = 'The {{ name }} value is not a valid choice, {{ value }} given. Accepted values are: {{ constraints }}.',
        string $multipleMessage = 'The {{ name }} value has one or more invalid choices, {{ value }} given. Accepted values are: {{ constraints }}.',
        string $minMessage = 'The {{ name }} value must have at least {{ min }} choices, {{ numValues }} choices given.',
        string $maxMessage = 'The {{ name }} value must have at most {{ max }} choices, {{ numValues }} choices given.'
    ): ChainedValidatorInterface&Validator;

    public static function country(
        string $code = 'alpha-2',
        string $message = 'The {{ name }} value is not a valid {{ code }} country code, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function eachKey(
        Validator $validator,
        string $message = 'Invalid key: {{ message }}'
    ): ChainedValidatorInterface&Validator;

    public static function eachValue(
        Validator $validator,
        string $message = 'At key {{ key }}: {{ message }}'
    ): ChainedValidatorInterface&Validator;

    public static function email(
        string $mode = 'html5',
        ?callable $normalizer = null,
        string $message = 'The {{ name }} value is not a valid email address, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function greaterThan(
        mixed $constraint,
        string $message = 'The {{ name }} value should be greater than {{ constraint }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function greaterThanOrEqual(
        mixed $constraint,
        string $message = 'The {{ name }} value should be greater than or equal to {{ constraint }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function lessThan(
        mixed $constraint,
        string $message = 'The {{ name }} value should be less than {{ constraint }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function lessThanOrEqual(
        mixed $constraint,
        string $message = 'The {{ name }} value should be less than or equal to {{ constraint }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function notBlank(
        ?callable $normalizer = null,
        string $message = 'The {{ name }} value should not be blank, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function range(
        mixed $min,
        mixed $max,
        string $message = 'The {{ name }} value should be between {{ min }} and {{ max }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function rule(
        RuleInterface $constraint
    ): ChainedValidatorInterface&Validator;

    public static function timezone(
        int $timezoneGroup = \DateTimeZone::ALL,
        ?string $countryCode = null,
        string $message = 'The {{ name }} value is not a valid timezone, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function type(
        string|array $constraint,
        string $message = 'The {{ name }} value should be of type {{ constraint }}, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;

    public static function url(
        array $protocols = ['http', 'https'],
        bool $allowRelativeProtocol = false,
        ?callable $normalizer = null,
        string $message = 'The {{ name }} value is not a valid URL address, {{ value }} given.'
    ): ChainedValidatorInterface&Validator;
}