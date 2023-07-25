<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

class AbstractRule
{
    private string $name = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}