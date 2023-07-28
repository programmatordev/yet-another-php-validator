<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception\Util;

trait FormatValueTrait
{
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
            return $value;
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