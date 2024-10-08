<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\LengthException;
use ProgrammatorDev\Validator\Exception\InvalidOptionException;
use ProgrammatorDev\Validator\Exception\OptionDefinitionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class Length extends AbstractRule implements RuleInterface
{
    public const COUNT_UNIT_BYTES = 'bytes';
    public const COUNT_UNIT_CODEPOINTS = 'codepoints';
    public const COUNT_UNIT_GRAPHEMES = 'graphemes';

    private const COUNT_UNITS = [
        self::COUNT_UNIT_BYTES,
        self::COUNT_UNIT_CODEPOINTS,
        self::COUNT_UNIT_GRAPHEMES
    ];

    /** @var ?callable */
    private $normalizer;
    private string $minMessage = 'The {{ name }} value should have {{ min }} character(s) or more.';
    private string $maxMessage = 'The {{ name }} value should have {{ max }} character(s) or less.';
    private string $exactMessage = 'The {{ name }} value should have exactly {{ min }} characters.';
    private string $charsetMessage = 'The {{ name }} value does not match the expected {{ charset }} charset.';

    public function __construct(
        private readonly ?int $min = null,
        private readonly ?int $max = null,
        private readonly string $charset = 'UTF-8',
        private readonly string $countUnit = self::COUNT_UNIT_CODEPOINTS,
        ?callable $normalizer = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $exactMessage = null,
        ?string $charsetMessage = null
    )
    {
        $this->normalizer = $normalizer;
        $this->minMessage = $minMessage ?? $this->minMessage;
        $this->maxMessage = $maxMessage ?? $this->maxMessage;
        $this->exactMessage = $exactMessage ?? $this->exactMessage;
        $this->charsetMessage = $charsetMessage ?? $this->charsetMessage;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->min === null && $this->max === null) {
            throw new OptionDefinitionException('At least one of the "min" or "max" options must be specified.');
        }

        $encodings = \mb_list_encodings();

        if (!\in_array($this->charset, $encodings)) {
            throw new InvalidOptionException('charset', $encodings);
        }

        if (!\in_array($this->countUnit, self::COUNT_UNITS)) {
            throw new InvalidOptionException('countUnit', self::COUNT_UNITS);
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedTypeException($value, 'string|\Stringable');
        }

        $value = (string) $value;

        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
        }

        if (!\mb_check_encoding($value, $this->charset)) {
            throw new LengthException(
                message: $this->charsetMessage,
                parameters: [
                    'name' => $name,
                    'value' => $value,
                    'charset' => $this->charset
                ]
            );
        }

        $numChars = match ($this->countUnit) {
            self::COUNT_UNIT_BYTES => \strlen($value),
            self::COUNT_UNIT_CODEPOINTS => \mb_strlen($value, $this->charset),
            self::COUNT_UNIT_GRAPHEMES => \grapheme_strlen($value),
        };

        if ($this->min !== null && $numChars < $this->min) {
            $message = ($this->min === $this->max) ? $this->exactMessage : $this->minMessage;

            throw new LengthException(
                message: $message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'min' => $this->min,
                    'max' => $this->max,
                    'numChars' => $numChars,
                    'charset' => $this->charset,
                    'countUnit' => $this->countUnit
                ]
            );
        }

        if ($this->max !== null && $numChars > $this->max) {
            $message = ($this->min === $this->max) ? $this->exactMessage : $this->maxMessage;

            throw new LengthException(
                message: $message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'min' => $this->min,
                    'max' => $this->max,
                    'numChars' => $numChars,
                    'charset' => $this->charset,
                    'countUnit' => $this->countUnit
                ]
            );
        }
    }
}