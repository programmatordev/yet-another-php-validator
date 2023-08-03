<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanOrEqualException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\IsComparableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GreaterThanOrEqual extends AbstractRule implements RuleInterface
{
    use IsComparableTrait;

    private array $options;

    public function __construct(private readonly mixed $constraint, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be greater than or equal to "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws GreaterThanOrEqualException
     */
    public function assert(mixed $value, string $name): void
    {
        if (!$this->isComparable($this->constraint, $value)) {
            throw new UnexpectedComparableException(
                get_debug_type($this->constraint),
                get_debug_type($value)
            );
        }

        if (!($value >= $this->constraint)) {
            throw new GreaterThanOrEqualException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraint' => $this->constraint
                ]
            );
        }
    }
}