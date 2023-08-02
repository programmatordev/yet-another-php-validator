<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

trait AssertIsValidatableTrait
{
    private function assertIsValidatable(array $constraints): bool
    {
        foreach ($constraints as $constraint) {
            if (!$constraint instanceof RuleInterface) {
                throw new UnexpectedValueException(
                    \sprintf('Expected constraint of type "RuleInterface", "%s" given.', get_debug_type($constraint))
                );
            }
        }

        return true;
    }
}