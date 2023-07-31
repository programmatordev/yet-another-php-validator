<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Factory;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RuleNotFoundException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class Factory
{
    private string $namespace = 'ProgrammatorDev\\YetAnotherPhpValidator\\Rule';

    /**
     * @throws RuleNotFoundException
     */
    public function createRule(string $ruleName, array $arguments = []): RuleInterface
    {
        $className = \sprintf('%s\\%s', $this->namespace, \ucfirst($ruleName));

        if (!class_exists($className)) {
            throw new RuleNotFoundException(
                \sprintf('"%s" rule does not exist.', $ruleName)
            );
        }

        return new $className(...$arguments);
    }
}