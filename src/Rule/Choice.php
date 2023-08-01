<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ChoiceException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Choice extends AbstractRule implements RuleInterface
{
    private array $options;

    public function __construct(
        private readonly array $constraints,
        private readonly bool $multiple = false,
        private readonly ?int $minConstraint = null,
        private readonly ?int $maxConstraint = null,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'message' => 'The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
            'multipleMessage' => 'The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
            'minMessage' => 'The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.',
            'maxMessage' => 'The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.'
        ]);

        $resolver->setAllowedTypes('message', 'string');
        $resolver->setAllowedTypes('multipleMessage', 'string');
        $resolver->setAllowedTypes('minMessage', 'string');
        $resolver->setAllowedTypes('maxMessage', 'string');

        $this->options = $resolver->resolve($options);
    }

    public function assert(mixed $value, string $name): void
    {
        if ($this->multiple && !\is_array($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "array" when multiple, "%s" given', get_debug_type($value))
            );
        }

        if ($this->multiple) {
            foreach ($value as $input) {
                if (!\in_array($input, $this->constraints, true)) {
                    throw new ChoiceException(
                        message: $this->options['multipleMessage'],
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
                    message: $this->options['minMessage'],
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
                    message: $this->options['maxMessage'],
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
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraints' => $this->constraints
                ]
            );
        }
    }
}