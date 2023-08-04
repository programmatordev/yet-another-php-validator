<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanOrEqualException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessThanOrEqual extends AbstractComparisonRule implements RuleInterface
{
    protected array $options;

    public function __construct(
        protected readonly mixed $constraint,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be less than or equal to "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    protected function comparison(mixed $constraint, mixed $value): bool
    {
        return $value <= $constraint;
    }

    protected function getException(): string
    {
        return LessThanOrEqualException::class;
    }
}