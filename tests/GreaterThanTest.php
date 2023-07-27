<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\Util\FormatValueTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class GreaterThanTest extends AbstractTest
{
    use FormatValueTrait;

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

    #[DataProvider('provideFailureConditionData')]
    public function testGreaterThanFailureCondition(mixed $constraint, mixed $value)
    {
        $validator = Validator::greaterThan($constraint);

        $this->assertFalse($validator->validate($value));

        $this->expectException(GreaterThanException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'The "test" value should be greater than "%s", "%s" given.',
                $this->formatValue($constraint),
                $this->formatValue($value)
            )
        );
        $validator->assert($value, 'test');
    }

    public static function provideFailureConditionData(): \Generator
    {
        yield 'datetime' => [new \DateTime('today'), new \DateTime('yesterday')];
        yield 'same datetime' => [new \DateTime('2000-01-01 00:00:00'), new \DateTime('2000-01-01 00:00:00')];
        yield 'int' => [10, 1];
        yield 'same int' => [10, 10];
        yield 'float' => [10.0, 1.0];
        yield 'same float' => [10.0, 10.0];
        yield 'int with float' => [10, 1.0];
        yield 'same int with float' => [10, 10.0];
        yield 'string' => ['z', 'a'];
        yield 'same string' => ['a', 'a'];
    }

    #[DataProvider('provideSuccessConditionData')]
    public function testGreaterThanSuccessCondition(mixed $constraint, mixed $value)
    {
        $validator = Validator::greaterThan($constraint);

        $this->assertTrue($validator->validate($value));

        $validator->assert($value, 'test');
    }

    public static function provideSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new \DateTime('today'), new \DateTime('tomorrow')];
        yield 'int' => [10, 20];
        yield 'float' => [10.0, 20.0];
        yield 'int with float' => [10, 20.0];
        yield 'string' => ['a', 'z'];
    }

    public function testGreaterThanMessageArgument()
    {
        $this->expectExceptionMessage('The "test" value "1" is invalid. Must not be greater than "10".');

        Validator
            ::greaterThan(
                constraint: 10,
                message: 'The "{{ name }}" value "{{ value }}" is invalid. Must not be greater than "{{ constraint }}".'
            )
            ->assert(1, 'test');
    }
}