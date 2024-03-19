<?php

namespace ProgrammatorDev\Validator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\Validator\Rule\RuleInterface;

trait TestRuleMessageOptionTrait
{
    public static abstract function provideRuleMessageOptionData(): \Generator;

    #[DataProvider('provideRuleMessageOptionData')]
    public function testRuleMessageOption(RuleInterface $rule, mixed $value, string $exceptionMessage): void
    {
        $this->expectExceptionMessage($exceptionMessage);
        $rule->assert($value, 'test');
    }
}