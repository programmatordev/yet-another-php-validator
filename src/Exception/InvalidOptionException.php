<?php

namespace ProgrammatorDev\Validator\Exception;

class InvalidOptionException extends UnexpectedValueException
{
    public function __construct(string $name, string|array|null $expected = null)
    {
        $message = \sprintf('The "%s" option is not valid.', $name);

        if (\is_array($expected)) {
            $message = \sprintf('%s Accepted values are: "%s".', $message, \implode('", "', $expected));
        }
        else if (\is_string($expected)) {
            $message = \sprintf('%s %s', $message, $expected);
        }

        parent::__construct($message);
    }
}