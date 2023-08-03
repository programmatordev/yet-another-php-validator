<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

trait IsComparableTrait
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
}