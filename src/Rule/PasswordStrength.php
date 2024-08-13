<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\PasswordStrengthException;
use ProgrammatorDev\Validator\Exception\UnexpectedOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class PasswordStrength extends AbstractRule implements RuleInterface
{
    private const STRENGTH_VERY_WEAK = 'very-weak';
    public const STRENGTH_WEAK = 'weak';
    public const STRENGTH_MEDIUM = 'medium';
    public const STRENGTH_STRONG = 'strong';
    public const STRENGTH_VERY_STRONG = 'very-strong';

    private const STRENGTH_OPTIONS = [
        self::STRENGTH_WEAK,
        self::STRENGTH_MEDIUM,
        self::STRENGTH_STRONG,
        self::STRENGTH_VERY_STRONG
    ];

    private const STRENGTH_SCORE = [
        self::STRENGTH_VERY_WEAK => 0,
        self::STRENGTH_WEAK => 1,
        self::STRENGTH_MEDIUM => 2,
        self::STRENGTH_STRONG => 3,
        self::STRENGTH_VERY_STRONG => 4
    ];

    private string $message = 'The password strength is not strong enough.';

    public function __construct(
        private readonly string $minStrength = self::STRENGTH_MEDIUM,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(#[\SensitiveParameter] mixed $value, ?string $name = null): void
    {
        if (!\in_array($this->minStrength, self::STRENGTH_OPTIONS)) {
            throw new UnexpectedOptionException('minStrength', self::STRENGTH_OPTIONS, $this->minStrength);
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $minScore = self::STRENGTH_SCORE[$this->minStrength];
        $score = self::STRENGTH_SCORE[$this->calcStrength($value)];

        if ($minScore > $score) {
            throw new PasswordStrengthException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'minStrength' => $this->minStrength
                ]
            );
        }
    }

    private function calcStrength(#[\SensitiveParameter] string $password): string
    {
        $length = \strlen($password);
        $chars = \count_chars($password, 1);

        $control = $digit = $upper = $lower = $symbol = $other = 0;
        foreach ($chars as $char => $count) {
            match (true) {
                ($char < 32 || $char === 127) => $control = 33,
                ($char >= 48 && $char <= 57) => $digit = 10,
                ($char >= 65 && $char <= 90) => $upper = 26,
                ($char >= 97 && $char <= 122) => $lower = 26,
                ($char >= 128) => $other = 128,
                default => $symbol = 33,
            };
        }

        $pool = $control + $digit + $upper + $lower + $symbol + $other;
        $entropy = \log(\pow($pool, $length), 2);

        return match (true) {
            $entropy >= 128 => self::STRENGTH_VERY_STRONG,
            $entropy >= 96 => self::STRENGTH_STRONG,
            $entropy >= 80 => self::STRENGTH_MEDIUM,
            $entropy >= 64 => self::STRENGTH_WEAK,
            default => self::STRENGTH_VERY_WEAK
        };
    }
}