<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;

class GreaterThan extends AbstractRule implements RuleInterface
{
    private string $message;

    public function __construct(
        private readonly mixed $constraint,
        string $message = null)
    {
        $this->message = $message ?? 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.';
    }

    /**
     * @throws GreaterThanException
     */
    public function validate(mixed $value): void
    {
        if (!$this->canBeCompared($this->constraint, $value)) {
            throw new \LogicException(
                \sprintf(
                    'Cannot compare a constraint type "%s" with a value type "%s"',
                    get_debug_type($this->constraint),
                    get_debug_type($value)
                )
            );
        }

        if (!($value > $this->constraint)) {
            throw new GreaterThanException(
                message: $this->message,
                parameters: [
                    'name' => $this->getName(),
                    'constraint' => $this->constraint,
                    'value' => $value
                ]
            );
        }
    }

    protected function canBeCompared(mixed $value1, mixed $value2): bool
    {
        if ($value1 instanceof \DateTimeInterface && $value2 instanceof \DateTimeInterface) {
            return true;
        }

        if (\is_numeric($value1) && \is_numeric($value2)) {
            return true;
        }

        if (\is_string($value1) && \is_string($value2)) {
            return true;
        }

        return false;
    }
}