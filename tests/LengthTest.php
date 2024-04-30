<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\LengthException;
use ProgrammatorDev\Validator\Rule\Length;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class LengthTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $value = 'abcde';

        $unexpectedMissingMinMaxMessage = '/At least one of the options "min" or "max" must be given\./';
        $unexpectedMinMaxMessage = '/Maximum value must be greater than or equal to minimum value\./';
        $unexpectedOptionMessage = '/Invalid (.*) "(.*)"\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string\|\\\Stringable", "(.*)" given\./';

        yield 'missing min max' => [new Length(), $value, $unexpectedMissingMinMaxMessage];
        yield 'min greater than max constraint' => [new Length(min: 3, max: 2), $value, $unexpectedMinMaxMessage];
        yield 'invalid charset value' => [new Length(min: 2, charset: 'INVALID'), $value, $unexpectedOptionMessage];
        yield 'invalid count unit value' => [new Length(min: 2, countUnit: 'invalid'), $value, $unexpectedOptionMessage];
        yield 'invalid value type' => [new Length(min: 2), [$value], $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = 'abcde';
        $exception = LengthException::class;

        $minMessage = '/The (.*) value should have (.*) characters or more, (.*) characters given\./';
        $maxMessage = '/The (.*) value should have (.*) characters or less, (.*) characters given\./';
        $exactMessage = '/The (.*) value should have exactly (.*) characters, (.*) characters given\./';
        $charsetMessage = '/The (.*) value does not match the expected (.*) charset\./';

        yield 'min constraint' => [new Length(min: 10), $value, $exception, $minMessage];
        yield 'max constraint' => [new Length(max: 2), $value, $exception, $maxMessage];
        yield 'min and max constraint' => [new Length(min: 10, max: 20), $value, $exception, $minMessage];
        yield 'exact constraint' => [new Length(min: 2, max: 2), $value, $exception, $exactMessage];
        yield 'charset' => [new Length(min: 2, charset: 'ASCII'), 'テスト', $exception, $charsetMessage];
        yield 'count unit' => [new Length(min: 1, max: 1, countUnit: 'bytes'), '🔥', $exception, $exactMessage];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        $value = 'abcde';

        yield 'min constraint' => [new Length(min: 2), $value];
        yield 'max constraint' => [new Length(max: 10), $value];
        yield 'min and max constraint' => [new Length(min: 4, max: 6), $value];
        yield 'exact constraint' => [new Length(min: 5, max: 5), $value];
        yield 'charset' => [new Length(min: 1, charset: 'ISO-8859-1'), $value];
        yield 'count unit' => [new Length(min: 4, max: 4, countUnit: 'bytes'), '🔥'];
        yield 'normalizer' => [new Length(max: 5, normalizer: 'trim'), 'abcde '];
        yield 'stringable' => [new Length(min: 4), 12345];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        $value = 'abcde';

        yield 'min message' => [
            new Length(
                min: 10,
                minMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numChars }} | {{ charset }} | {{ countUnit }}'
            ),
            $value,
            'test | "abcde" | 10 | null | 5 | "UTF-8" | "codepoints"'
        ];
        yield 'max message' => [
            new Length(
                max: 2,
                maxMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numChars }} | {{ charset }} | {{ countUnit }}'
            ),
            $value,
            'test | "abcde" | null | 2 | 5 | "UTF-8" | "codepoints"'
        ];
        yield 'exact message' => [
            new Length(
                min: 2,
                max: 2,
                exactMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numChars }} | {{ charset }} | {{ countUnit }}'
            ),
            $value,
            'test | "abcde" | 2 | 2 | 5 | "UTF-8" | "codepoints"'
        ];
        yield 'charset message' => [
            new Length(
                min: 2,
                charset: 'ASCII',
                charsetMessage: '{{ name }} | {{ value }} | {{ charset }}'
            ),
            'テスト',
            'test | "テスト" | "ASCII"'
        ];
    }
}