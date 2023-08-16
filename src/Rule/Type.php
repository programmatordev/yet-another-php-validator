<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\TypeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;

class Type extends AbstractRule implements RuleInterface
{
    public const BOOL = 'bool';
    public const BOOLEAN = 'boolean';
    public const INT = 'int';
    public const INTEGER = 'integer';
    public const LONG = 'long';
    public const FLOAT = 'float';
    public const DOUBLE = 'double';
    public const REAL = 'real';
    public const NUMERIC = 'numeric';
    public const STRING = 'string';
    public const SCALAR = 'scalar';
    public const ARRAY = 'array';
    public const ITERABLE = 'iterable';
    public const COUNTABLE = 'countable';
    public const CALLABLE = 'callable';
    public const OBJECT = 'object';
    public const RESOURCE = 'resource';
    public const NULL = 'null';
    public const ALPHANUMERIC = 'alphanumeric';
    public const ALPHA = 'alpha';
    public const DIGIT = 'digit';
    public const CONTROL = 'control';
    public const PUNCTUATION = 'punctuation';
    public const HEXADECIMAL = 'hexadecimal';
    public const GRAPH = 'graph';
    public const PRINTABLE = 'printable';
    public const WHITESPACE = 'whitespace';
    public const LOWERCASE = 'lowercase';
    public const UPPERCASE = 'uppercase';

    private const TYPE_FUNCTIONS = [
        self::BOOL => 'is_bool',
        self::BOOLEAN => 'is_bool',
        self::INT => 'is_int',
        self::INTEGER => 'is_int',
        self::LONG => 'is_int',
        self::FLOAT => 'is_float',
        self::DOUBLE => 'is_float',
        self::REAL => 'is_float',
        self::NUMERIC => 'is_numeric',
        self::STRING => 'is_string',
        self::SCALAR => 'is_scalar',
        self::ARRAY => 'is_array',
        self::ITERABLE => 'is_iterable',
        self::COUNTABLE => 'is_countable',
        self::CALLABLE => 'is_callable',
        self::OBJECT => 'is_object',
        self::RESOURCE => 'is_resource',
        self::NULL => 'is_null',
        self::ALPHANUMERIC => 'ctype_alnum',
        self::ALPHA => 'ctype_alpha',
        self::DIGIT => 'ctype_digit',
        self::CONTROL => 'ctype_cntrl',
        self::PUNCTUATION => 'ctype_punct',
        self::HEXADECIMAL => 'ctype_xdigit',
        self::GRAPH => 'ctype_graph',
        self::PRINTABLE => 'ctype_print',
        self::WHITESPACE => 'ctype_space',
        self::LOWERCASE => 'ctype_lower',
        self::UPPERCASE => 'ctype_upper'
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

            if (!isset(self::TYPE_FUNCTIONS[$constraint]) && !\class_exists($constraint) && !\interface_exists($constraint)) {
                throw new UnexpectedValueException(
                    \sprintf(
                        'Invalid constraint type "%s". Accepted values are: "%s"',
                        $constraint,
                        \implode('", "', \array_keys(self::TYPE_FUNCTIONS))
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