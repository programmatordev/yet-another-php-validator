<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\Util\FormatValueTrait;

class ValidationException extends \Exception
{
    use FormatValueTrait;

    public function __construct(string $message, array $parameters = [])
    {
        $message = $this->formatMessage($message, $parameters);
        parent::__construct($message);
    }

    private function formatMessage(string $message, array $parameters = []): string
    {
        foreach ($parameters as $parameter => $value) {
            $message = str_replace("{{ $parameter }}", $this->formatValue($value), $message);
        }

        return $message;
    }


}