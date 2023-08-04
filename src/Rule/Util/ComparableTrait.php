<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

trait ComparableTrait
{
    private function isComparable(mixed $value1, mixed $value2): bool
    {
        if ($value1 instanceof \DateTimeInterface && $value2 instanceof \DateTimeInterface) {
            return true;
        }

        if (\is_numeric($value1) && \is_numeric($value2)) {
            return true;
        }

        if (\is_string($value1) && \is_string($value2)) {
            return true;
        }

        return false;
    }

    private function convertToComparable(mixed $value): mixed
    {
        // Try to guess if it is a valid datetime format,
        // like "yesterday" or "1985-07-19"
        // https://www.php.net/manual/en/datetime.formats.php
        if (\is_string($value) && \mb_strlen($value) > 1) {
            try {
                return new \DateTimeImmutable($value);
            }
            catch (\Exception) {
                return $value;
            }
        }

        return $value;
    }
}