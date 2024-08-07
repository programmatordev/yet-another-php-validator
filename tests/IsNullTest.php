<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\IsNullException;
use ProgrammatorDev\Validator\Rule\IsNull;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class IsNullTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = IsNullException::class;
        $message = '/The (.*) value should be null, (.*) given\./';

        yield 'int' => [new IsNull(), 1, $exception, $message];
        yield 'string' => [new IsNull(), 'string', $exception, $message];
        yield 'bool' => [new IsNull(), true, $exception, $message];
        yield 'array' => [new IsNull(), [], $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'null' => [new IsNull(), null];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new IsNull(
                message: '{{ name }} | {{ value }}'
            ),
            'string',
            'test | "string"'
        ];
    }
}