<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule\Util;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

trait ValidatableTrait
{
    private function isValidatable(array $constraints): bool
    {
        foreach ($constraints as $constraint) {
            if (!$constraint instanceof RuleInterface) {
                return false;
            }
        }

        return true;
    }
}