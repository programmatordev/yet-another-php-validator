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

        $unexpectedMissingMinMaxMessage = '/At least one of the options "min" or "max" must be given./';
        $unexpectedMinMaxMessage = '/Maximum value must be greater than or equal to minimum value./';
        $unexpectedOptionMessage = '/Invalid (.*) "(.*)". Accepted values are: "(.*)"./';
        $unexpectedTypeMessage = '/Expected value of type "array|\Stringable", "(.*)" given./';

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

        $minMessage = '/The (.*) value should have (.*) characters or more, (.*) characters given./';
        $maxMessage = '/The (.*) value should have (.*) characters or less, (.*) characters given./';
        $exactMessage = '/The (.*) value should have exactly (.*) characters, (.*) characters given./';
        $charsetMessage = '/The (.*) value does not match the expected (.*) charset./';

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
        yield 'stringable' => [new Length(min: 4), 12345];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        $value = 'abcde';

        yield 'min message' => [
            new Length(
                min: 10,
                minMessage: 'The {{ name }} value with count unit {{ countUnit }} should have at least {{ min }} characters.'
            ),
            $value,
            'The test value with count unit "codepoints" should have at least 10 characters.'
        ];
        yield 'max message' => [
            new Length(
                max: 2,
                maxMessage: 'The {{ name }} value with count unit {{ countUnit }} should have at most {{ max }} characters.'
            ),
            $value,
            'The test value with count unit "codepoints" should have at most 2 characters.'
        ];
        yield 'exact message' => [
            new Length(
                min: 2,
                max: 2,
                exactMessage: 'The {{ name }} value with count unit {{ countUnit }} should have exactly {{ min }} characters.'
            ),
            $value,
            'The test value with count unit "codepoints" should have exactly 2 characters.'
        ];
        yield 'charset message' => [
            new Length(
                min: 2,
                charset: 'ASCII',
                charsetMessage: 'The {{ name }} value is not a {{ charset }} charset.'
            ),
            'テスト',
            'The test value is not a "ASCII" charset.'
        ];
    }
}