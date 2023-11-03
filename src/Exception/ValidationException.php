<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

class ValidationException extends \Exception
{
    public function __construct(string $message, array $parameters = [])
    {
        $message = $this->formatMessage($message, $parameters);

        parent::__construct($message);
    }

    private function formatMessage(string $message, array $parameters = []): string
    {
        // If a name was not given, remove it from the message template but keep it intuitive
        if (empty($parameters['name'])) {
            $message = \str_replace(' {{ name }} ', ' ', $message);
            unset($parameters['name']);
        }

        foreach ($parameters as $parameter => $value) {
            // Format values (with some exceptions [name, message] to avoid adding unnecessary quotation marks)
            $message = \str_replace(
                \sprintf('{{ %s }}', $parameter),
                (\in_array($parameter, ['name', 'message'])) ? $value : $this->formatValue($value),
                $message
            );
        }

        return $message;
    }

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
            // Replace line breaks and tabs with single space
            $value = \str_replace(["\n", "\r", "\t", "\v", "\x00"], ' ', $value);

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

    private function formatValues(array $values): string
    {
        foreach ($values as $key => $value) {
            $values[$key] = $this->formatValue($value);
        }

        return \sprintf('[%s]', \implode(', ', $values));
    }
}