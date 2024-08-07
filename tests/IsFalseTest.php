<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\IsFalseException;
use ProgrammatorDev\Validator\Rule\IsFalse;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class IsFalseTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = IsFalseException::class;
        $message = '/The (.*) value should be false, (.*) given\./';

        yield 'int' => [new IsFalse(), 1, $exception, $message];
        yield 'string' => [new IsFalse(), 'string', $exception, $message];
        yield 'true' => [new IsFalse(), true, $exception, $message];
        yield 'array' => [new IsFalse(), [], $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'false' => [new IsFalse(), false];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new IsFalse(
                message: '{{ name }} | {{ value }}'
            ),
            true,
            'test | true'
        ];
    }
}