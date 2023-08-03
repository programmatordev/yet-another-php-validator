<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

class UnexpectedComparableException extends UnexpectedValueException
{
    public function __construct(string $value1, string $value2)
    {
        $message = \sprintf('Cannot compare a type "%s" with a type "%s".', $value1, $value2);

        parent::__construct($message);
    }
}