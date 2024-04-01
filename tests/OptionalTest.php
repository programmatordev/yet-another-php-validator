<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\NotBlankException;
use ProgrammatorDev\Validator\Rule\NotBlank;
use ProgrammatorDev\Validator\Rule\Optional;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Validator;

class OptionalTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        yield 'default' => [
            new Optional(
                new Validator(new NotBlank())
            ),
            '',
            NotBlankException::class,
            '/The (.*) value should not be blank, (.*) given\./'
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'default' => [
            new Optional(
                new Validator(new NotBlank())
            ),
            null
        ];
    }
}