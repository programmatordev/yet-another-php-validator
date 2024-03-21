<?php

namespace ProgrammatorDev\Validator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\Validator\Rule\RuleInterface;

trait TestRuleFailureConditionTrait
{
    public static abstract function provideRuleFailureConditionData(): \Generator;

    #[DataProvider('provideRuleFailureConditionData')]
    public function testRuleFailureCondition(
        RuleInterface $rule,
        mixed $value,
        string $expectedException,
        string $expectedExceptionMessage
    ): void
    {
        $this->assertFalse($rule->validate($value));

        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($expectedExceptionMessage);
        $rule->assert($value, 'test');
    }
}