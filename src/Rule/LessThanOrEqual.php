<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanOrEqualException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertIsComparableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessThanOrEqual extends AbstractRule implements RuleInterface
{
    use AssertIsComparableTrait;

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
        $this->assertIsComparable($this->constraint, $value);

        if (!($value <= $this->constraint)) {
            throw new LessThanOrEqualException(
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