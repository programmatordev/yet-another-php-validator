<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

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