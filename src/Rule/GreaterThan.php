<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertComparableTrait;

class GreaterThan extends AbstractRule implements RuleInterface
{
    use AssertComparableTrait;

    private string $message;

    public function __construct(
        private readonly mixed $constraint,
        string $message = null
    )
    {
        $this->message = $message ?? 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.';
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
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'constraint' => $this->constraint,
                    'value' => $value
                ]
            );
        }
    }
}