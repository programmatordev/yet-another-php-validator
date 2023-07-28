<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class GreaterThanTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;

    #[DataProvider('provideInvalidConditionData')]
    public function testGreaterThanValidateInvalidCondition(mixed $constraint, mixed $value)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Cannot compare a constraint type "%s" with a value type "%s"',
                get_debug_type($constraint),
                get_debug_type($value)
            )
        );

        Validator::greaterThan($constraint)->validate($value);
    }

    #[DataProvider('provideInvalidConditionData')]
    public function testGreaterThanAssertInvalidCondition(mixed $constraint, mixed $value)
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Cannot compare a constraint type "%s" with a value type "%s"',
                get_debug_type($constraint),
                get_debug_type($value)
            )
        );

        Validator::greaterThan($constraint)->assert($value, 'test');
    }

    public static function provideInvalidConditionData(): \Generator
    {
        yield 'datetime constraint with int value' => [new \DateTime(), 10];
        yield 'datetime constraint with float value' => [new \DateTime(), 1.0];
        yield 'datetime constraint with string value' => [new \DateTime(), 'a'];
        yield 'int constraint with string value' => [10, 'a'];
        yield 'float constraint with string value' => [1.0, 'a'];
        yield 'array constraint' => [[10], 10];
        yield 'null constraint' => [null, 10];
    }

    public static function provideFailureConditionData(): \Generator
    {
        $exception = GreaterThanException::class;
        $exceptionMessage = '/The "(.*)" value should be greater than "(.*)", "(.*)" given./';

        yield 'datetime' => [
            new GreaterThan(new \DateTime('today')),
            new \DateTime('yesterday'),
            $exception,
            $exceptionMessage
        ];
        yield 'same datetime' => [
            new GreaterThan(new \DateTime('2000-01-01')),
            new \DateTime('2000-01-01'),
            $exception,
            $exceptionMessage
        ];
        yield 'int' => [new GreaterThan(10), 1, $exception, $exceptionMessage];
        yield 'same int' => [new GreaterThan(10), 10, $exception, $exceptionMessage];
        yield 'float' => [new GreaterThan(10.0), 1.0, $exception, $exceptionMessage];
        yield 'same float' => [new GreaterThan(10.0), 10.0, $exception, $exceptionMessage];
        yield 'int with float' => [new GreaterThan(10), 1.0, $exception, $exceptionMessage];
        yield 'same int with float' => [new GreaterThan(10), 10.0, $exception, $exceptionMessage];
        yield 'string' => [new GreaterThan('z'), 'a', $exception, $exceptionMessage];
        yield 'same string' => [new GreaterThan('a'), 'a', $exception, $exceptionMessage];
    }

    public static function provideSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('tomorrow')];
        yield 'int' => [new GreaterThan(10), 20];
        yield 'float' => [new GreaterThan(10.0), 20.0];
        yield 'int with float' => [new GreaterThan(10), 20.0];
        yield 'string' => [new GreaterThan('a'), 'z'];
    }

//    public function testGreaterThanMessageArgument()
//    {
//        $this->expectExceptionMessage('The "test" value "1" is invalid. Must not be greater than "10".');
//
//        Validator
//            ::greaterThan(
//                constraint: 10,
//                message: 'The "{{ name }}" value "{{ value }}" is invalid. Must not be greater than "{{ constraint }}".'
//            )
//            ->assert(1, 'test');
//    }
}