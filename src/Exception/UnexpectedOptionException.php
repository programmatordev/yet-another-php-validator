<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

class UnexpectedOptionException extends UnexpectedValueException
{
    public function __construct(string $name, array $expected, string $given)
    {
        $message = \sprintf('Invalid %s "%s". Accepted values are: "%s".', $name, $given, \implode('", "', $expected));

        parent::__construct($message);
    }
}