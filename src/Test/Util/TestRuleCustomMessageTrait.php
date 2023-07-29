<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

trait TestRuleCustomMessageTrait
{
    public static abstract function provideRuleCustomMessageData(): \Generator;

    #[DataProvider('provideRuleCustomMessageData')]
    public function testRuleCustomMessage(RuleInterface $rule, mixed $value, string $exceptionMessage): void
    {
        $this->expectExceptionMessage($exceptionMessage);
        $rule->assert($value, 'test');
    }
}