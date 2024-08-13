<?php

namespace ProgrammatorDev\Validator\Exception;

class UnexpectedTypeException extends UnexpectedValueException
{
    public function __construct(mixed $value, string $expectedType)
    {
        $message = \sprintf('Expected value of type "%s", "%s" given.', $expectedType, \get_debug_type($value));

        parent::__construct($message);
    }
}