<?php

namespace ProgrammatorDev\Validator\Exception;

class UnexpectedComparableException extends UnexpectedValueException
{
    public function __construct(mixed $value1, mixed $value2)
    {
        $message = \sprintf(
            'Cannot compare a type "%s" with a type "%s".',
            \get_debug_type($value1),
            \get_debug_type($value2)
        );

        parent::__construct($message);
    }
}