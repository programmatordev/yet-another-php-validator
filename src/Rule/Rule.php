<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

class Rule extends AbstractRule implements RuleInterface
{
    public function __construct(private readonly RuleInterface $constraint) {}

    public function assert(mixed $value, string $name): void
    {
        $this->constraint->assert($value, $name);
    }
}