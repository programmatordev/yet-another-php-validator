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
        yield 'invalid value type' => [
            new Rule(new DummyRule()),
            'invalid',
            '/Dummy unexpected value./'
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        yield 'invalid value' => [
            new Rule(new DummyRule()),
            false,
            ValidationException::class,
            '/Dummy exception./'
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