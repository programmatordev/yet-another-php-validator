<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

class ValidationException extends \Exception
{

    /**
     * @throws \Exception
     */
    public function __construct(string $message, array $parameters = [])
    {
        $message = $this->formatMessage($message, $parameters);
        parent::__construct($message);
    }

    /**
     * @throws \Exception
     */
    private function formatMessage(string $message, array $parameters = []): string
    {
        foreach ($parameters as $parameter => $value) {
            $message = str_replace("{{ $parameter }}", $this->formatValue($value), $message);
        }

        return $message;
    }

    /**
     * @throws \Exception
     */
    private function formatValue(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (\is_object($value)) {
            if ($value instanceof \Stringable) {
                return $value->__toString();
            }

            return 'object';
        }

        if (\is_array($value)) {
            return $this->formatValues($value);
        }

        if (\is_string($value)) {
            return \sprintf('"%s"', $value);
        }

        if (\is_resource($value)) {
            return 'resource';
        }

        if ($value === null) {
            return 'null';
        }

        if ($value === false) {
            return 'false';
        }

        if ($value === true) {
            return 'true';
        }

        return (string) $value;
    }

    /**
     * @throws \Exception
     */
    private function formatValues(array $values): string
    {
        foreach ($values as $key => $value) {
            $values[$key] = $this->formatValue($value);
        }

        return \implode(', ', $values);
    }
}