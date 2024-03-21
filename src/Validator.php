<?php

namespace ProgrammatorDev\Validator;

use ProgrammatorDev\Validator\Exception\RuleNotFoundException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Factory\Factory;
use ProgrammatorDev\Validator\Rule\RuleInterface;

/**
 * @mixin StaticValidatorInterface
 */
class Validator implements RuleInterface
{
    /** @var RuleInterface[] */
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
        if (empty($this->rules)) {
            throw new UnexpectedValueException('Validator rules not found: at least one rule is required.');
        }

        foreach ($this->rules as $rule) {
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