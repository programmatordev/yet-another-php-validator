<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class NotBlankTest extends AbstractTest
{
    #[DataProvider('provideInvalidInputData')]
    public function testNotBlankInvalidInput(mixed $input)
    {
        $validator = Validator::notBlank();

        $this->assertFalse($validator->validate($input));

        $this->expectException(NotBlankException::class);
        $this->expectExceptionMessage('The "test" value should not be blank.');
        $validator->assert($input, 'test');
    }

    public static function provideInvalidInputData(): \Generator
    {
        yield 'null' => [null];
        yield 'false' => [false];
        yield 'blank array' => [[]];
        yield 'blank string' => [''];
        yield 'whitespace' => [' '];
    }

    #[DataProvider('provideValidInputData')]
    public function testNotBlankValidInput(mixed $input)
    {
        $validator = Validator::notBlank();

        $this->assertTrue($validator->validate($input));

        Validator::notBlank()->assert($input, 'test');
    }

    public static function provideValidInputData(): \Generator
    {
        yield 'true' => [true];
        yield 'zero number' => [0];
        yield 'zero string' => ['0'];
        yield 'array' => [[0]];
        yield 'string' => ['string'];
    }

    public function testNotBlankExceptionMessageParameters()
    {
        $this->expectExceptionMessage('The "test" value false is invalid. Must not be blank.');

        Validator::notBlank(
            message: 'The {{ name }} value {{ input }} is invalid. Must not be blank.'
        )->assert(false, 'test');
    }
}