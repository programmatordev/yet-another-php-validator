<?php

namespace ProgrammatorDev\YetAnotherPhpValidator;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RuleNotFoundException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Factory\Factory;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

/**
 * @mixin StaticValidatorInterface
 */
class Validator implements RuleInterface
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
     * @throws RuleNotFoundException
     */
    public static function __callStatic(string $ruleName, array $arguments = []): self
    {
        return self::create()->__call($ruleName, $arguments);
    }

    /**
     * @throws RuleNotFoundException
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
    public function assert(mixed $value, ?string $name = null): void
    {
        if (empty($this->getRules())) {
            throw new UnexpectedValueException('Validator rules not found: at least one rule is required.');
        }

        foreach ($this->getRules() as $rule) {
            $rule->assert($value, $name);
        }
    }

    public function validate(mixed $value): bool
    {
        try {
            $this->assert($value);
        }
        catch (ValidationException) {
            return false;
        }

        return true;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }
}