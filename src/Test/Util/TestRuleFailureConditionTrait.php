<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

trait TestRuleFailureConditionTrait
{
    public static abstract function provideFailureConditionData(): \Generator;

    #[DataProvider('provideFailureConditionData')]
    public function testRuleFailureCondition(
        RuleInterface $rule,
        mixed $value,
        string $expectedException,
        string $expectedExceptionMessage
    )
    {
        $this->assertFalse($rule->validate($value));

        $this->expectException($expectedException);
        $this->expectExceptionMessageMatches($expectedExceptionMessage);
        $rule->assert($value, 'test');
    }
}