<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Validator;

class Optional extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly Validator $validator
    ) {}

    public function assert(mixed $value, ?string $name = null): void
    {
        // validate only if value is not null
        if ($value === null) {
            return;
        }

        $this->validator->assert($value, $name);
    }
}