<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\AtLeastOneOfException;
use ProgrammatorDev\Validator\Rule\AtLeastOneOf;
use ProgrammatorDev\Validator\Rule\Blank;
use ProgrammatorDev\Validator\Rule\IsFalse;
use ProgrammatorDev\Validator\Rule\Length;
use ProgrammatorDev\Validator\Rule\Type;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\Validator\Validator;

class AtLeastOneOfTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "constraints" option is not valid. All values should be of type "ProgrammatorDev\\\Validator\\\Validator"./';

        yield 'invalid option constraints' => [new AtLeastOneOf(['invalid']), 'string', $invalidOptionMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = AtLeastOneOfException::class;
        $message = '/The (.*) value should satisfy at least one of the following constraints\: (.*)\./';

        yield 'constraints' => [
            new AtLeastOneOf([
                new Validator(new IsFalse()),
                new Validator(new Type('string'), new Length(10)),
            ]), 'invalid', $exception, $message
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'constraints 1' => [
            new AtLeastOneOf([
                new Validator(new IsFalse()),
                new Validator(new Type('string'), new Length(10)),
            ]),
            false
        ];
        yield 'constraints 2' => [
            new AtLeastOneOf([
                new Validator(new IsFalse()),
                new Validator(new Type('string'), new Length(10)),
            ]),
            'valid string'
        ];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new AtLeastOneOf(
                constraints: [new Validator(new Blank())],
                message: '{{ name }} | {{ value }} | {{ messages }}'
            ),
            'string',
            'test | "string" | [1] The value should be blank.'
        ];
    }
}