<?php

namespace ProgrammatorDev\Validator\Exception;

use ProgrammatorDev\Validator\Exception\Util\MessageTrait;

class ValidationException extends \Exception
{
    use MessageTrait;

    public function __construct(string $message, array $parameters = [])
    {
        $message = $this->formatMessage($message, $parameters);

        parent::__construct($message);
    }
}