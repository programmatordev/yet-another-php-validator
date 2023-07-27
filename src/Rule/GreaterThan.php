<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;

class GreaterThan extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly mixed $constraint,
        private ?string $message = null)
    {
        $this->message ??= 'The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.';
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

    protected function canBeCompared(mixed $constraint, mixed $value): bool
    {
        if ($constraint instanceof \DateTimeInterface && $value instanceof \DateTimeInterface) {
            return true;
        }

        if (\is_numeric($constraint) && \is_numeric($value)) {
            return true;
        }

        if (\is_string($constraint) && \is_string($value)) {
            return true;
        }

        return false;
    }
}