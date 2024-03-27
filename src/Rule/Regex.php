<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\RegexException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;

class Regex extends AbstractRule implements RuleInterface
{
    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value is not valid.';

    public function __construct(
        private readonly string $pattern,
        private readonly bool $match = true,
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer = $normalizer;
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedTypeException('string|\Stringable', get_debug_type($value));
        }

        $value = (string) $value;

        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
        }

        if (($regex = @\preg_match($this->pattern, $value)) === false) {
            throw new UnexpectedValueException('Invalid regular expression pattern.');
        }

        if ($this->match xor $regex) {
            throw new RegexException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'value' => $value,
                    'pattern' => $this->pattern
                ]
            );
        }
    }
}