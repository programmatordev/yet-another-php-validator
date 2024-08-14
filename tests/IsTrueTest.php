<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\IsTrueException;
use ProgrammatorDev\Validator\Rule\IsTrue;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class IsTrueTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = IsTrueException::class;
        $message = '/The (.*) value should be true\./';

        yield 'int' => [new IsTrue(), 1, $exception, $message];
        yield 'string' => [new IsTrue(), 'string', $exception, $message];
        yield 'false' => [new IsTrue(), false, $exception, $message];
        yield 'array' => [new IsTrue(), [], $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'true' => [new IsTrue(), true];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new IsTrue(
                message: '{{ name }} | {{ value }}'
            ),
            false,
            'test | false'
        ];
    }
}