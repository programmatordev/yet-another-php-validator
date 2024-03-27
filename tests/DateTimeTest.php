<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\DateTimeException;
use ProgrammatorDev\Validator\Rule\DateTime;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class DateTimeTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Expected value of type "string|\Stringable", "(.*)" given./';

        yield 'invalid value type' => [new DateTime(), ['2024-01-01 00:00:00'], $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = 'invalid';
        $exception = DateTimeException::class;
        $message = '/The (.*) value is not a valid datetime./';

        yield 'invalid datetime' => [new DateTime(), $value, $exception, $message];
        yield 'invalid format' => [new DateTime(format: 'invalid'), '2024-01-01 00:00:00', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new DateTime(), '2024-01-01 00:00:00'];
        yield 'format' => [new DateTime(format: 'Y-m-d'), '2024-01-01'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new DateTime(
                message: 'The {{ name }} datetime does not match the format {{ format }}.'
            ),
            '2024-01-01',
            'The test datetime does not match the format "Y-m-d H:i:s".'
        ];
    }
}