<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\TypeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;

class Type extends AbstractRule implements RuleInterface
{
    private const TYPE_FUNCTIONS = [
        'bool' => 'is_bool',
        'boolean' => 'is_bool',
        'int' => 'is_int',
        'integer' => 'is_int',
        'long' => 'is_int',
        'float' => 'is_float',
        'double' => 'is_float',
        'real' => 'is_float',
        'numeric' => 'is_numeric',
        'string' => 'is_string',
        'scalar' => 'is_scalar',
        'array' => 'is_array',
        'iterable' => 'is_iterable',
        'countable' => 'is_countable',
        'callable' => 'is_callable',
        'object' => 'is_object',
        'resource' => 'is_resource',
        'null' => 'is_null',
        'alphanumeric' => 'ctype_alnum',
        'alpha' => 'ctype_alpha',
        'digit' => 'ctype_digit',
        'control' => 'ctype_cntrl',
        'punctuation' => 'ctype_punct',
        'hexadecimal' => 'ctype_xdigit',
        'graph' => 'ctype_graph',
        'printable' => 'ctype_print',
        'whitespace' => 'ctype_space',
        'lowercase' => 'ctype_lower',
        'uppercase' => 'ctype_upper'
    ];

    public function __construct(
        private readonly string|array $constraint,
        private readonly string $message = 'The "{{ name }}" value should be of type "{{ constraint }}", "{{ value }}" given.'
    ) {}

    public function assert(mixed $value, string $name): void
    {
        $constraints = (array) $this->constraint;

        foreach ($constraints as $constraint) {
            if (isset(self::TYPE_FUNCTIONS[$constraint]) && (self::TYPE_FUNCTIONS[$constraint])($value)) {
                return;
            }

            if ($value instanceof $constraint) {
                return;
            }

            if (!isset(self::TYPE_FUNCTIONS[$constraint]) && !class_exists($constraint) && !interface_exists($constraint)) {
                throw new UnexpectedValueException(
                    \sprintf(
                        'Invalid constraint type "%s". Accepted values are: "%s"',
                        $constraint,
                        implode(', ', array_keys(self::TYPE_FUNCTIONS))
                    )
                );
            }
        }

        throw new TypeException(
            message: $this->message,
            parameters: [
                'name' => $name,
                'value' => $value,
                'constraint' => $this->constraint
            ]
        );
    }
}