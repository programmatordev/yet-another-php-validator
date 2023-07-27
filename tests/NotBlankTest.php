<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\Util\FormatValueTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class NotBlankTest extends AbstractTest
{
    use FormatValueTrait;

    #[DataProvider('provideFailureConditionData')]
    public function testNotBlankFailureCondition(mixed $value)
    {
        $validator = Validator::notBlank();

        $this->assertFalse($validator->validate($value));

        $this->expectException(NotBlankException::class);
        $this->expectExceptionMessage(\sprintf('The "test" value should not be blank, "%s" given.', $this->formatValue($value)));
        $validator->assert($value, 'test');
    }

    public static function provideFailureConditionData(): \Generator
    {
        yield 'null' => [null];
        yield 'false' => [false];
        yield 'blank string' => [''];
        yield 'blank array' => [[]];
    }

    #[DataProvider('provideSuccessConditionData')]
    public function testNotBlankSuccessCondition(mixed $value)
    {
        $validator = Validator::notBlank();

        $this->assertTrue($validator->validate($value));

        $validator->assert($value, 'test');
    }

    public static function provideSuccessConditionData(): \Generator
    {
        yield 'true' => [true];

        yield 'string' => ['string'];
        yield 'whitespace string' => [' '];
        yield 'zero string' => ['0'];

        yield 'array' => [['string']];
        yield 'blank string array' => [['']];
        yield 'whitespace array' => [[' ']];
        yield 'zero array' => [[0]];

        yield 'number' => [10];
        yield 'zero number' => [0];
    }

    public function testNotBlankMessageArgument()
    {
        $this->expectExceptionMessage('The "test" value "" is invalid. Must not be blank.');

        Validator
            ::notBlank(message: 'The "{{ name }}" value "{{ value }}" is invalid. Must not be blank.')
            ->assert('', 'test');
    }
}