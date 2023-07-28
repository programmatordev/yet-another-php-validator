<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

trait AssertComparableTrait
{
    private function assertComparable(mixed $value1, mixed $value2, string $exception): bool
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

        throw new $exception(
            \sprintf(
                'Cannot compare a type "%s" with a type "%s"',
                get_debug_type($value1),
                get_debug_type($value2)
            )
        );
    }
}