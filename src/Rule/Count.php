<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\CountException;
use ProgrammatorDev\Validator\Exception\OptionDefinitionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class Count extends AbstractRule implements RuleInterface
{
    private string $minMessage = 'The {{ name }} value should contain {{ min }} elements or more.';
    private string $maxMessage = 'The {{ name }} value should contain {{ max }} elements or less.';
    private string $exactMessage = 'The {{ name }} value should contain exactly {{ min }} elements.';

    public function __construct(
        private readonly ?int $min = null,
        private readonly ?int $max = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $exactMessage = null
    )
    {
        $this->minMessage = $minMessage ?? $this->minMessage;
        $this->maxMessage = $maxMessage ?? $this->maxMessage;
        $this->exactMessage = $exactMessage ?? $this->exactMessage;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->min === null && $this->max === null) {
            throw new OptionDefinitionException('At least one of the "min" or "max" options must be specified.');
        }

        if (!\is_countable($value)) {
            throw new UnexpectedTypeException($value, 'array|\Countable');
        }

        $numElements = \count($value);

        if ($this->min !== null && $numElements < $this->min) {
            $message = ($this->min === $this->max) ? $this->exactMessage : $this->minMessage;

            throw new CountException(
                message: $message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'min' => $this->min,
                    'max' => $this->max,
                    'numElements' => $numElements
                ]
            );
        }

        if ($this->max !== null && $numElements > $this->max) {
            $message = ($this->min === $this->max) ? $this->exactMessage : $this->maxMessage;

            throw new CountException(
                message: $message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'min' => $this->min,
                    'max' => $this->max,
                    'numElements' => $numElements
                ]
            );
        }
    }
}