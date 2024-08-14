<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\CollectionException;
use ProgrammatorDev\Validator\Exception\InvalidOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class Collection extends AbstractRule implements RuleInterface
{
    private string $message = '{{ message }}';
    private string $extraFieldsMessage = 'The {{ field }} field is not allowed.';
    private string $missingFieldsMessage = 'The {{ field }} field is missing.';

    /** @param array<mixed, Validator> $fields */
    public function __construct(
        private readonly array $fields,
        private readonly bool $allowExtraFields = false,
        ?string $message = null,
        ?string $extraFieldsMessage = null,
        ?string $missingFieldsMessage = null
    )
    {
        $this->message = $message ?? $this->message;
        $this->extraFieldsMessage = $extraFieldsMessage ?? $this->extraFieldsMessage;
        $this->missingFieldsMessage = $missingFieldsMessage ?? $this->missingFieldsMessage;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        try {
            Validator::eachValue(
                validator: Validator::type(Validator::class),
            )->assert($this->fields);
        }
        catch (ValidationException) {
            throw new InvalidOptionException(
                name: 'fields',
                expected: \sprintf('All values should be of type "%s".', Validator::class)
            );
        }

        if (!\is_iterable($value)) {
            throw new UnexpectedTypeException($value, 'array|\Traversable');
        }

        foreach ($this->fields as $field => $validator) {
            // find if validation is optional
            $isOptional = $validator->getRules()[0] instanceof Optional;

            if (!isset($value[$field]) && !$isOptional) {
                throw new CollectionException(
                    message: $this->missingFieldsMessage,
                    parameters: [
                        'name' => $name,
                        'field' => $field
                    ]
                );
            }

            // if value is not set but field is optional
            if (!isset($value[$field]) && $isOptional) {
                $value[$field] = null;
            }

            try {
                $validator->assert($value[$field], \sprintf('"%s"', $field));
            }
            catch (ValidationException $exception) {
                throw new CollectionException(
                    message: $this->message,
                    parameters: [
                        'name' => $name,
                        'field' => $field,
                        'message' => $exception->getMessage()
                    ]
                );
            }
        }

        if (!$this->allowExtraFields) {
            foreach ($value as $field => $fieldValue) {
                if (!isset($this->fields[$field])) {
                    throw new CollectionException(
                        message: $this->extraFieldsMessage,
                        parameters: [
                            'name' => $name,
                            'field' => $field
                        ]
                    );
                }
            }
        }
    }
}