<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RangeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
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
            'message' => 'The "{{ name }}" value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.'
        ]);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    public function assert(mixed $value, string $name): void
    {
        $this->assertIsComparable($this->minConstraint, $this->maxConstraint);

        if (!Validator::greaterThan($this->minConstraint)->validate($this->maxConstraint)) {
            throw new UnexpectedValueException(
                'Max constraint value must be greater than min constraint value.'
            );
        }

        if (!Validator::greaterThanOrEqual($this->minConstraint)->lessThanOrEqual($this->maxConstraint)->validate($value)) {
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