<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Factory;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RuleNotFoundException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class Factory
{
    private array $namespaces = ['ProgrammatorDev\\YetAnotherPhpValidator\\Rule'];

    /**
     * @throws RuleNotFoundException
     */
    public function createRule(string $ruleName, array $arguments = []): RuleInterface
    {
        foreach ($this->namespaces as $namespace) {
            $className = \sprintf('%s\\%s', $namespace, \ucfirst($ruleName));

            if (class_exists($className)) {
                return new $className(...$arguments);
            }
        }

        throw new RuleNotFoundException(
            \sprintf('"%s" rule does not exist.', $ruleName)
        );
    }
}