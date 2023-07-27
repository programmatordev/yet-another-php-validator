<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

interface RuleInterface
{
    /**
     * @throws ValidationException
     */
    public function validate(mixed $value): void;

    public function getName(): string;

    public function setName(string $name): static;
}