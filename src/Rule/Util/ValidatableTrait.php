<?php

namespace ProgrammatorDev\Validator\Rule\Util;

use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Rule\RuleInterface;

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