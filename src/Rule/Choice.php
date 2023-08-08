<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ChoiceException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;

class Choice extends AbstractRule implements RuleInterface
{
    private string $message;

    private string $multipleMessage;

    private string $minMessage;

    private string $maxMessage;

    public function __construct(
        private readonly array $constraints,
        private readonly bool $multiple = false,
        private readonly ?int $minConstraint = null,
        private readonly ?int $maxConstraint = null,
        ?string $message = null,
        ?string $multipleMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null
    )
    {
        $this->message = $message ?? 'The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".';
        $this->multipleMessage = $multipleMessage ?? 'The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".';
        $this->minMessage = $minMessage ?? 'The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.';
        $this->maxMessage = $maxMessage ?? 'The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.';
    }

    public function assert(mixed $value, string $name): void
    {
        if ($this->multiple && !\is_array($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "array" when using multiple choices, "%s" given', get_debug_type($value))
            );
        }

        if (
            $this->multiple
            && $this->minConstraint !== null
            && $this->maxConstraint !== null
            && $this->minConstraint > $this->maxConstraint
        ) {
            throw new UnexpectedValueException(
                'Max constraint value must be greater than or equal to min constraint value.'
            );
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

            $numValues = \count($value);

            if ($this->minConstraint !== null && $numValues < $this->minConstraint) {
                throw new ChoiceException(
                    message: $this->minMessage,
                    parameters: [
                        'value' => $value,
                        'numValues' => $numValues,
                        'name' => $name,
                        'constraints' => $this->constraints,
                        'minConstraint' => $this->minConstraint,
                        'maxConstraint' => $this->maxConstraint
                    ]
                );
            }

            if ($this->maxConstraint !== null && $numValues > $this->maxConstraint) {
                throw new ChoiceException(
                    message: $this->maxMessage,
                    parameters: [
                        'value' => $value,
                        'numValues' => $numValues,
                        'name' => $name,
                        'constraints' => $this->constraints,
                        'minConstraint' => $this->minConstraint,
                        'maxConstraint' => $this->maxConstraint
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