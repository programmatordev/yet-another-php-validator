<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\RegexException;
use ProgrammatorDev\Validator\Rule\Regex;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class RegexTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedPatternMessage = '/Invalid regular expression pattern\./';
        $unexpectedTypeMessage = '/Expected value of type "string\|\\\Stringable", "(.*)" given\./';

        yield 'invalid pattern' => [new Regex('invalid'), 'abc', $unexpectedPatternMessage];
        yield 'invalid value type' => [new Regex('/[a-z]/'), ['abc'], $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = 'abc';
        $exception = RegexException::class;
        $message = '/The (.*) value is not valid\./';

        yield 'match true' => [new Regex('/[0-9]/'), $value, $exception, $message];
        yield 'match false' => [new Regex('/[a-z]/', match: false), $value, $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        $value = 'abc';

        yield 'match true' => [new Regex('/[a-z]/'), $value];
        yield 'match false' => [new Regex('/[0-9]/', match: false), $value];
        yield 'normalizer' => [new Regex('/^\S*$/', normalizer: 'trim'), 'abc '];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Regex(
                pattern: '/[a-z]/',
                message: 'The {{ name }} value does not match the pattern {{ pattern }}.'
            ),
            '123',
            'The test value does not match the pattern "/[a-z]/".'
        ];
    }
}