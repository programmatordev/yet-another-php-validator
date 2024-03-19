<?php

namespace ProgrammatorDev\Validator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Rule\RuleInterface;

trait TestRuleUnexpectedValueTrait
{
    public static abstract function provideRuleUnexpectedValueData(): \Generator;

    #[DataProvider('provideRuleUnexpectedValueData')]
    public function testRuleAssertUnexpectedValue(RuleInterface $rule, mixed $value, string $expectedExceptionMessage): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageMatches($expectedExceptionMessage);
        $rule->assert($value, 'test');
    }

    #[DataProvider('provideRuleUnexpectedValueData')]
    public function testRuleValidateUnexpectedValue(RuleInterface $rule, mixed $value, string $expectedExceptionMessage): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageMatches($expectedExceptionMessage);
        $rule->validate($value);
    }
}