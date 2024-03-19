<?php

namespace ProgrammatorDev\Validator\Exception;

class UnexpectedTypeException extends UnexpectedValueException
{
    public function __construct(string $expected, string $given)
    {
        $message = \sprintf('Expected value of type "%s", "%s" given.', $expected, $given);

        parent::__construct($message);
    }
}