<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

interface StaticValidatorInterface
{
    public static function notBlank(array $options = []): ChainedValidatorInterface;

    public static function greaterThan(mixed $constraint, array $options = []): ChainedValidatorInterface;

    public static function lessThan(mixed $constraint, array $options = []): ChainedValidatorInterface;
}