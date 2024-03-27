<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Rule\Rule;
use ProgrammatorDev\Validator\Test\Fixture\DummyRule;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class RuleTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Dummy unexpected value\./';

        yield 'invalid value type' => [
            new Rule(new DummyRule()),
            'invalid',
            $unexpectedTypeMessage
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = ValidationException::class;
        $message = '/Dummy exception\./';

        yield 'invalid value' => [
            new Rule(new DummyRule()),
            false,
            $exception,
            $message
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'valid value' => [
            new Rule(new DummyRule()),
            true
        ];
    }

}