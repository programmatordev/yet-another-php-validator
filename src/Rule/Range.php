<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RangeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ComparableTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Range extends AbstractRule implements RuleInterface
{
    use ComparableTrait;

    private array $options;

    public function __construct(
        private readonly mixed $minConstraint,
        private readonly mixed $maxConstraint,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be between "{{ minConstraint }}" and "{{ maxConstraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    public function assert(mixed $value, string $name): void
    {
        $minConstraint = $this->convertToComparable($this->minConstraint);
        $maxConstraint = $this->convertToComparable($this->maxConstraint);
        $value = $this->convertToComparable($value);

        if (!$this->isComparable($minConstraint, $maxConstraint)) {
            throw new UnexpectedComparableException(
                get_debug_type($minConstraint),
                get_debug_type($maxConstraint)
            );
        }

        if (
            !Validator::greaterThan($minConstraint)
                ->validate($maxConstraint)
        ) {
            throw new UnexpectedValueException(
                'Max constraint value must be greater than min constraint value.'
            );
        }

        if (
            !Validator::greaterThanOrEqual($minConstraint)
                ->lessThanOrEqual($maxConstraint)
                ->validate($value)
        ) {
            throw new RangeException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'minConstraint' => $minConstraint,
                    'maxConstraint' => $maxConstraint
                ]
            );
        }
    }
}