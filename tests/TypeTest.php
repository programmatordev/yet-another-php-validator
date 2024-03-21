<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\TypeException;
use ProgrammatorDev\Validator\Rule\Type;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class TypeTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Invalid constraint type "(.*)". Accepted values are: "(.*)"/';

        yield 'invalid type' => [new Type('invalid'), 'string', $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = TypeException::class;
        $message = '/The (.*) value should be of type (.*), (.*) given./';

        yield 'bool' => [new Type('bool'), 'invalid', $exception, $message];
        yield 'boolean' => [new Type('boolean'), 'invalid', $exception, $message];
        yield 'int' => [new Type('int'), 'invalid', $exception, $message];
        yield 'integer' => [new Type('integer'), 'invalid', $exception, $message];
        yield 'long' => [new Type('long'), 'invalid', $exception, $message];
        yield 'float' => [new Type('float'), 'invalid', $exception, $message];
        yield 'double' => [new Type('double'), 'invalid', $exception, $message];
        yield 'real' => [new Type('real'), 'invalid', $exception, $message];
        yield 'numeric' => [new Type('numeric'), 'invalid', $exception, $message];
        yield 'string' => [new Type('string'), 123, $exception, $message];
        yield 'scalar' => [new Type('scalar'), [], $exception, $message];
        yield 'array' => [new Type('array'), 'invalid', $exception, $message];
        yield 'iterable' => [new Type('iterable'), 'invalid', $exception, $message];
        yield 'countable' => [new Type('countable'), 'invalid', $exception, $message];
        yield 'callable' => [new Type('callable'), 'invalid', $exception, $message];
        yield 'object' => [new Type('object'), 'invalid', $exception, $message];
        yield 'resource' => [new Type('resource'), 'invalid', $exception, $message];
        yield 'null' => [new Type('null'), 'invalid', $exception, $message];
        yield 'alphanumeric' => [new Type('alphanumeric'), 'foo!#$bar', $exception, $message];
        yield 'alpha' => [new Type('alpha'), 'arf12', $exception, $message];
        yield 'digit' => [new Type('digit'), 'invalid', $exception, $message];
        yield 'control' => [new Type('control'), 'arf12', $exception, $message];
        yield 'punctuation' => [new Type('punctuation'), 'ABasdk!@!$#', $exception, $message];
        yield 'hexadecimal' => [new Type('hexadecimal'), 'AR1012', $exception, $message];
        yield 'graph' => [new Type('graph'), "asdf\n\r\t", $exception, $message];
        yield 'printable' => [new Type('printable'), "asdf\n\r\t", $exception, $message];
        yield 'whitespace' => [new Type('whitespace'), "\narf12", $exception, $message];
        yield 'lowercase' => [new Type('lowercase'), 'Invalid', $exception, $message];
        yield 'uppercase' => [new Type('uppercase'), 'invalid', $exception, $message];

        yield 'class' => [new Type(\DateTime::class), 'invalid', $exception, $message];
        yield 'interface' => [new Type(\DateTimeInterface::class), 'invalid', $exception, $message];

        yield 'multiple types' => [new Type(['digit', 'numeric']), 'invalid', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'bool' => [new Type('bool'), true];
        yield 'boolean' => [new Type('boolean'), false];
        yield 'int' => [new Type('int'), 1];
        yield 'integer' => [new Type('integer'), 2];
        yield 'long' => [new Type('long'), 3];
        yield 'float' => [new Type('float'), 1.1];
        yield 'double' => [new Type('double'), 1.2];
        yield 'real' => [new Type('real'), 1.3];
        yield 'numeric' => [new Type('numeric'), 123];
        yield 'string' => [new Type('string'), 'string'];
        yield 'scalar' => [new Type('scalar'), 'string'];
        yield 'array' => [new Type('array'), [1, 2, 3]];
        yield 'iterable' => [new Type('iterable'), new \ArrayIterator([1, 2, 3])];
        yield 'countable' => [new Type('countable'), new \ArrayIterator([1, 2, 3])];
        yield 'callable' => [new Type('callable'), 'trim'];
        yield 'object' => [new Type('object'), new \stdClass()];
        yield 'resource' => [new Type('resource'), fopen('php://stdout', 'r')];
        yield 'null' => [new Type('null'), null];
        yield 'alphanumeric' => [new Type('alphanumeric'), 'abc123'];
        yield 'alpha' => [new Type('alpha'), 'abc'];
        yield 'digit' => [new Type('digit'), '123'];
        yield 'control' => [new Type('control'), "\n\r\t"];
        yield 'punctuation' => [new Type('punctuation'), '*&$()'];
        yield 'hexadecimal' => [new Type('hexadecimal'), 'AB10BC99'];
        yield 'graph' => [new Type('graph'), 'LKA#@%.54'];
        yield 'printable' => [new Type('printable'), 'LKA#@%.54'];
        yield 'whitespace' => [new Type('whitespace'), "\n\r\t"];
        yield 'lowercase' => [new Type('lowercase'), 'string'];
        yield 'uppercase' => [new Type('uppercase'), 'STRING'];

        yield 'class' => [new Type(\DateTime::class), new \DateTime()];
        yield 'interface' => [new Type(\DateTimeInterface::class), new \DateTime()];

        yield 'multiple types' => [new Type(['alpha', 'numeric']), '123'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Type(
                constraint: 'int',
                message: 'The {{ name }} value is not of type {{ constraint }}.'
            ),
            'string',
            'The test value is not of type "int".'
        ];
    }

}