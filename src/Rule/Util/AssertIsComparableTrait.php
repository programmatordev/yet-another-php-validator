<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;

trait AssertIsComparableTrait
{
    private function assertIsComparable(mixed $value1, mixed $value2): bool
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

        throw new UnexpectedValueException(
            \sprintf(
                'Cannot compare a type "%s" with a type "%s".',
                get_debug_type($value1),
                get_debug_type($value2)
            )
        );
    }
}