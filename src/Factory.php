<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\InvalidRuleException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class Factory
{
    private string $namespace = 'ProgrammatorDev\\YetAnotherPhpValidator\\Rule';

    /**
     * @throws InvalidRuleException
     */
    public function createRule(string $ruleName, array $arguments = []): RuleInterface
    {
        try {
            $className = \sprintf('%s\\%s', $this->namespace, \ucfirst($ruleName));
            return new $className(...$arguments);
        }
        catch (\Error) {
            throw new InvalidRuleException(
                \sprintf('"%s" rule does not exist.', $ruleName)
            );
        }
    }
}