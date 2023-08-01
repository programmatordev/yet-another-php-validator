<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RangeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertIsComparableTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Range extends AbstractRule implements RuleInterface
{
    use AssertIsComparableTrait;

    private array $options;

    public function __construct(
        private readonly mixed $minConstraint,
        private readonly mixed $maxConstraint,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'message' => 'The {{ name }} value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.'
        ]);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    public function assert(mixed $value, string $name): void
    {
        $this->assertIsComparable($this->minConstraint, $this->maxConstraint);

        try {
            Validator
                ::greaterThanOrEqual($this->minConstraint)
                ->lessThanOrEqual($this->maxConstraint)
                ->assert($value, $name);
        }
        catch (ValidationException) {
            throw new RangeException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'minConstraint' => $this->minConstraint,
                    'maxConstraint' => $this->maxConstraint
                ]
            );
        }
    }
}