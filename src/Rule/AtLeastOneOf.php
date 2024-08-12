<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\AtLeastOneOfException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class AtLeastOneOf extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value should satisfy at least one of the following constraints: {{ messages }}';

    /** @param Validator[] $constraints */
    public function __construct(
        private readonly array $constraints,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        try {
            Validator::eachValue(
                validator: Validator::type(Validator::class)
            )->assert($this->constraints);
        }
        catch (ValidationException $exception) {
            throw new UnexpectedValueException($exception->getMessage());
        }

        $messages = [];

        foreach ($this->constraints as $key => $constraint) {
            try {
                $constraint->assert($value, $name);
                return;
            }
            catch (ValidationException|UnexpectedValueException $exception) {
                $messages[] = \sprintf('[%d] %s', ($key + 1), $exception->getMessage());
            }
        }

        throw new AtLeastOneOfException(
            message: $this->message,
            parameters: [
                'value' => $value,
                'name' => $name,
                'messages' => \implode(' ', $messages)
            ]
        );
    }
}