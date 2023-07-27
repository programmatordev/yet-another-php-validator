<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\InvalidRuleException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

/**
 * @mixin StaticValidatorInterface
 */
class Validator
{
    private array $rules;

    public function __construct(RuleInterface ...$rules)
    {
        $this->rules = $rules;
    }

    private static function create(): self
    {
        return new self();
    }

    /**
     * @throws InvalidRuleException
     */
    public static function __callStatic(string $ruleName, array $arguments = []): self
    {
        return self::create()->__call($ruleName, $arguments);
    }

    /**
     * @throws InvalidRuleException
     */
    public function __call(string $ruleName, array $arguments = []): self
    {
        $factory = new Factory();
        $this->addRule($factory->createRule($ruleName, $arguments));

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function assert(mixed $value, string $name): void
    {
        foreach ($this->getRules() as $rule) {
            $rule->setName($name)->validate($value);
        }
    }

    public function validate(mixed $value): bool
    {
        try {
            $this->assert($value, 'null');
        }
        catch (ValidationException) {
            return false;
        }

        return true;
    }

    /**
     * @return RuleInterface[]
     */
    private function getRules(): array
    {
        return $this->rules;
    }

    private function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }
}