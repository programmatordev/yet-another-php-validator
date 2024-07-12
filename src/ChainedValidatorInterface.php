<?php

namespace ProgrammatorDev\Validator;

use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Rule\RuleInterface;

interface ChainedValidatorInterface
{
    public function blank(
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function choice(
        array $constraints,
        bool $multiple = false,
        ?int $min = null,
        ?int $max = null,
        ?string $message = null,
        ?string $multipleMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null
    ): ChainedValidatorInterface&Validator;

    public function collection(
        array $fields,
        bool $allowExtraFields = false,
        ?string $message = null,
        ?string $extraFieldsMessage = null,
        ?string $missingFieldsMessage = null
    ): ChainedValidatorInterface&Validator;

    public function count(
        ?int $min = null,
        ?int $max = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $exactMessage = null
    ): ChainedValidatorInterface&Validator;

    public function country(
        string $code = 'alpha-2',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function cssColor(
        ?array $formats = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function dateTime(
        string $format = 'Y-m-d H:i:s',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function eachKey(
        Validator $validator,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function eachValue(
        Validator $validator,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    static function email(
        string $mode = 'html5',
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function greaterThan(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function greaterThanOrEqual(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function language(
        string $code = 'alpha-2',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function length(
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

    public function lessThan(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function lessThanOrEqual(
        mixed $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function locale(
        bool $canonicalize = false,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function notBlank(
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function optional(
        Validator $validator
    ): ChainedValidatorInterface&Validator;

    public function passwordStrength(
        string $minStrength = 'medium',
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function range(
        mixed $min,
        mixed $max,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function regex(
        string $pattern,
        bool $match = true,
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function rule(
        RuleInterface $constraint
    ): ChainedValidatorInterface&Validator;

    public function timezone(
        int $timezoneGroup = \DateTimeZone::ALL,
        ?string $countryCode = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function type(
        string|array $constraint,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;

    public function url(
        array $protocols = ['http', 'https'],
        bool $allowRelativeProtocol = false,
        ?callable $normalizer = null,
        ?string $message = null
    ): ChainedValidatorInterface&Validator;
}