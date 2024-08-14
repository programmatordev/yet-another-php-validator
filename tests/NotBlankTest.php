<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\NotBlankException;
use ProgrammatorDev\Validator\Rule\NotBlank;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class NotBlankTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = NotBlankException::class;
        $message = '/The (.*) value should not be blank\./';

        yield 'null' => [new NotBlank(), null, $exception, $message];
        yield 'false' => [new NotBlank(), false, $exception, $message];
        yield 'blank string' => [new NotBlank(), '', $exception, $message];
        yield 'blank array' => [new NotBlank(), [], $exception, $message];

        yield 'normalizer whitespace' => [new NotBlank(normalizer: 'trim'), ' ', $exception, $message];
        yield 'normalizer whitespace function' => [new NotBlank(normalizer: fn($value) => trim($value)), ' ', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'true' => [new NotBlank(), true];

        yield 'string' => [new NotBlank(), 'string'];
        yield 'whitespace string' => [new NotBlank(), ' '];
        yield 'zero string' => [new NotBlank(), '0'];

        yield 'array' => [new NotBlank(), ['string']];
        yield 'blank string array' => [new NotBlank(), ['']];
        yield 'whitespace array' => [new NotBlank(), [' ']];
        yield 'zero array' => [new NotBlank(), [0]];

        yield 'number' => [new NotBlank(), 10];
        yield 'zero number' => [new NotBlank(), 0];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new NotBlank(
                message: '{{ name }} | {{ value }}'
            ),
            '',
            'test | ""'
        ];
    }
}