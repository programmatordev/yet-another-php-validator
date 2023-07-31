<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertComparableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessThan extends AbstractRule implements RuleInterface
{
    use AssertComparableTrait;

    private array $options;

    public function __construct(private readonly mixed $constraint, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should be less than "{{ constraint }}", "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws LessThanException
     */
    public function assert(mixed $value, string $name): void
    {
        // Assert if constraint and value can be compared
        $this->assertComparable($this->constraint, $value, LessThanException::class);

        if (!($value < $this->constraint)) {
            throw new LessThanException(
                message: $this->options['message'],
                parameters: [
                    'name' => $name,
                    'constraint' => $this->constraint,
                    'value' => $value
                ]
            );
        }
    }
}