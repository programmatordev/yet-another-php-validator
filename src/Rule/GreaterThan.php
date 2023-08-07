<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GreaterThan extends AbstractComparisonRule implements RuleInterface
{
    protected array $options;

    public function __construct(
        protected readonly mixed $constraint,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    protected function comparison(mixed $constraint, mixed $value): bool
    {
        if (\is_string($constraint) && \is_string($value)) {
            return strcmp($value, $constraint) > 0;
        }

        return $value > $constraint;
    }

    protected function getException(): string
    {
        return GreaterThanException::class;
    }
}