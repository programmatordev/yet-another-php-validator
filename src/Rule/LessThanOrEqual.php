<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanOrEqualException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedComparableException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ComparableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessThanOrEqual extends AbstractRule implements RuleInterface
{
    use ComparableTrait;

    private array $options;

    public function __construct(private readonly mixed $constraint, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be less than or equal to "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws LessThanOrEqualException
     */
    public function assert(mixed $value, string $name): void
    {
        $constraint = $this->convertToComparable($this->constraint);
        $value = $this->convertToComparable($value);

        if (!$this->isComparable($constraint, $value)) {
            throw new UnexpectedComparableException(
                get_debug_type($constraint),
                get_debug_type($value)
            );
        }

        if (!($value <= $constraint)) {
            throw new LessThanOrEqualException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'constraint' => $constraint
                ]
            );
        }
    }
}