<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Rule;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Fixture\DummyRule;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

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