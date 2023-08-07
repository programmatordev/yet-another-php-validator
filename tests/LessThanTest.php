<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\LessThan;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class LessThanTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $message = '/Cannot compare a type "(.*)" with a type "(.*)"/';

        yield 'datetime constraint with int value' => [new LessThan(new \DateTime()), 10, $message];
        yield 'datetime constraint with float value' => [new LessThan(new \DateTime()), 1.0, $message];
        yield 'datetime constraint with string value' => [new LessThan(new \DateTime()), 'a', $message];
        yield 'datetime string constraint with string value' => [new LessThan('now'), 'a', $message];
        yield 'int constraint with string value' => [new LessThan(10), 'a', $message];
        yield 'float constraint with string value' => [new LessThan(1.0), 'a', $message];
        yield 'array constraint' => [new LessThan([10]), 10, $message];
        yield 'null constraint' => [new LessThan(null), 10, $message];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LessThanException::class;
        $message = '/The "(.*)" value should be less than "(.*)", "(.*)" given./';

        yield 'datetime' => [new LessThan(new \DateTime('today')), new \DateTime('tomorrow'), $exception, $message];
        yield 'same datetime' => [new LessThan(new \DateTime('today')), new \DateTime('today'), $exception, $message];
        yield 'datetime string' => [new LessThan('today'), 'tomorrow', $exception, $message];
        yield 'same datetime string' => [new LessThan('today'), 'today', $exception, $message];
        yield 'int' => [new LessThan(10), 20, $exception, $message];
        yield 'same int' => [new LessThan(10), 10, $exception, $message];
        yield 'float' => [new LessThan(10.0), 20.0, $exception, $message];
        yield 'same float' => [new LessThan(10.0), 10.0, $exception, $message];
        yield 'int with float' => [new LessThan(10), 20.0, $exception, $message];
        yield 'same int with float' => [new LessThan(10), 10.0, $exception, $message];
        yield 'string' => [new LessThan('a'), 'z', $exception, $message];
        yield 'same string' => [new LessThan('a'), 'a', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new LessThan(new \DateTime('today')), new \DateTime('yesterday')];
        yield 'datetime string' => [new LessThan('today'), 'yesterday'];
        yield 'int' => [new LessThan(10), 1];
        yield 'float' => [new LessThan(10.0), 1.0];
        yield 'int with float' => [new LessThan(10), 1.0];
        yield 'string' => [new LessThan('z'), 'a'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new LessThan(
                constraint: 10,
                options: [
                    'message' => 'The "{{ name }}" value "{{ value }}" is not less than "{{ constraint }}".'
                ]
            ), 20, 'The "test" value "20" is not less than "10".'
        ];
    }
}