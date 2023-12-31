<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

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