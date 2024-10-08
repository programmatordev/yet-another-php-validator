<?php

namespace ProgrammatorDev\Validator\Exception\Util;

trait MessageTrait
{
    private function formatMessage(string $message, array $parameters = []): string
    {
        // if a name was not given, remove it from the message template but keep it intuitive
        if (empty($parameters['name'])) {
            $message = \str_replace(' {{ name }} ', ' ', $message);
            unset($parameters['name']);
        }

        foreach ($parameters as $parameter => $value) {
            // format values (with some exceptions to avoid adding unnecessary quotation marks)
            $message = \str_replace(
                \sprintf('{{ %s }}', $parameter),
                (\in_array($parameter, ['name', 'message', 'messages'])) ? $value : $this->formatValue($value),
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
            // replace line breaks and tabs with single space
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