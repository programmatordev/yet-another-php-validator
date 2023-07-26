<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

interface StaticValidatorInterface
{
    public static function notBlank(?string $message = null): ChainedValidatorInterface;

//    public static function greaterThan(mixed $constraint): ChainedValidatorInterface;
}