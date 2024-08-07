<?php

namespace ProgrammatorDev\Validator;

use ProgrammatorDev\Validator\Rule\RuleInterface;

interface StaticValidatorInterface
{
    public static function blank(
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function choice(
        array $constraints,
        bool $multiple = false,
        ?int $min = null,
        ?int $max = null,
        ?string $message = null,
        ?string $multipleMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null
    ): ChainedValidatorInterface&Validator;

    public static function collection(
        array $fields,
        bool $allowExtraFields = false,
        ?string $message = null,
        ?string $extraFieldsMessage = null,
        ?string $missingFieldsMessage = null
    ): ChainedValidatorInterface&Validator;

    public static function count(
        ?int $min = null,
        ?int $max = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $exactMessage = null
    ): ChainedValidatorInterface&Validator;

    public static function country(
        string $code = 'alpha-2',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function cssColor(
        ?array $formats = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function dateTime(
        string $format = 'Y-m-d H:i:s',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function eachKey(
        Validator $validator,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function eachValue(
        Validator $validator,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function email(
        string $mode = 'html5',
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function greaterThan(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function greaterThanOrEqual(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function isNull(
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function language(
        string $code = 'alpha-2',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function length(
        ?int $min = null,
        ?int $max = null,
        string $charset = 'UTF-8',
        string $countUnit = 'codepoints',
        ?callable $normalizer = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $exactMessage = null,
        ?string $charsetMessage = null
    ): ChainedValidatorInterface&Validator;

    public static function lessThan(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function lessThanOrEqual(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function locale(
        bool $canonicalize = false,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function notBlank(
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function notNull(
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function optional(
        Validator $validator
    ): ChainedValidatorInterface&Validator;

    public static function passwordStrength(
        string $minStrength = 'medium',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function range(
        mixed $min,
        mixed $max,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function regex(
        string $pattern,
        bool $match = true,
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function rule(
        RuleInterface $constraint
    ): ChainedValidatorInterface&Validator;

    public static function timezone(
        int $timezoneGroup = \DateTimeZone::ALL,
        ?string $countryCode = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function type(
        string|array $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public static function url(
        array $protocols = ['http', 'https'],
        bool $allowRelativeProtocol = false,
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;
}