<?php

namespace ProgrammatorDev\Validator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\Validator\Rule\RuleInterface;

trait TestRuleSuccessConditionTrait
{
    public static abstract function provideRuleSuccessConditionData(): \Generator;

    #[DataProvider('provideRuleSuccessConditionData')]
    public function testRuleSuccessCondition(RuleInterface $rule, mixed $value): void
    {
        $rule->assert($value, 'test');

        $this->assertTrue($rule->validate($value));
    }
}