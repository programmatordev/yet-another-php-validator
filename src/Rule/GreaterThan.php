<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertComparableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GreaterThan extends AbstractRule implements RuleInterface
{
    use AssertComparableTrait;

    private array $options;

    public function __construct(private readonly mixed $constraint, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws GreaterThanException
     */
    public function assert(mixed $value, string $name): void
    {
        // Assert if constraint and value can be compared
        $this->assertComparable($this->constraint, $value, GreaterThanException::class);

        if (!($value > $this->constraint)) {
            throw new GreaterThanException(
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