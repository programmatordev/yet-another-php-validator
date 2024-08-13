<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\ChoiceException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;

class Choice extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value is not a valid choice. Accepted values are: {{ constraints }}.';
    private string $multipleMessage = 'The {{ name }} value has one or more invalid choices. Accepted values are: {{ constraints }}.';
    private string $minMessage = 'The {{ name }} value must have at least {{ min }} choices.';
    private string $maxMessage = 'The {{ name }} value must have at most {{ max }} choices.';

    public function __construct(
        private readonly array $constraints,
        private readonly bool $multiple = false,
        private readonly ?int $min = null,
        private readonly ?int $max = null,
        ?string $message = null,
        ?string $multipleMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null
    )
    {
        $this->message = $message ?? $this->message;
        $this->multipleMessage = $multipleMessage ?? $this->multipleMessage;
        $this->minMessage = $minMessage ?? $this->minMessage;
        $this->maxMessage = $maxMessage ?? $this->maxMessage;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->multiple && !\is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        if (
            $this->multiple
            && $this->min !== null
            && $this->max !== null
            && $this->min > $this->max
        ) {
            throw new UnexpectedValueException('Maximum value must be greater than or equal to minimum value.');
        }

        if ($this->multiple) {
            foreach ($value as $input) {
                if (!\in_array($input, $this->constraints, true)) {
                    throw new ChoiceException(
                        message: $this->multipleMessage,
                        parameters: [
                            'value' => $value,
                            'name' => $name,
                            'constraints' => $this->constraints
                        ]
                    );
                }
            }

            $numElements = \count($value);

            if ($this->min !== null && $numElements < $this->min) {
                throw new ChoiceException(
                    message: $this->minMessage,
                    parameters: [
                        'value' => $value,
                        'name' => $name,
                        'constraints' => $this->constraints,
                        'min' => $this->min,
                        'max' => $this->max,
                        'numElements' => $numElements
                    ]
                );
            }

            if ($this->max !== null && $numElements > $this->max) {
                throw new ChoiceException(
                    message: $this->maxMessage,
                    parameters: [
                        'value' => $value,
                        'name' => $name,
                        'constraints' => $this->constraints,
                        'min' => $this->min,
                        'max' => $this->max,
                        'numElements' => $numElements
                    ]
                );
            }
        }
        else if (!\in_array($value, $this->constraints, true)) {
            throw new ChoiceException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraints' => $this->constraints
                ]
            );
        }
    }
}