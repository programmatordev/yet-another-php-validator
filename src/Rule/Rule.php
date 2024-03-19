<?php

namespace ProgrammatorDev\Validator\Rule;

class Rule extends AbstractRule implements RuleInterface
{
    public function __construct(private readonly RuleInterface $constraint) {}

    public function assert(mixed $value, ?string $name = null): void
    {
        $this->constraint->assert($value, $name);
    }
}