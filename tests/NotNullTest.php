<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\NotNullException;
use ProgrammatorDev\Validator\Rule\NotNull;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class NotNullTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = NotNullException::class;
        $message = '/The (.*) value should not be null\./';

        yield 'null' => [new NotNull(), null, $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'int' => [new NotNull(), 1];
        yield 'string' => [new NotNull(), 'string'];
        yield 'bool' => [new NotNull(), true];
        yield 'array' => [new NotNull(), []];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new NotNull(
                message: '{{ name }}'
            ),
            null,
            'test'
        ];
    }
}