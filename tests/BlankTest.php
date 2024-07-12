<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\BlankException;
use ProgrammatorDev\Validator\Rule\Blank;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;

class BlankTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = BlankException::class;
        $message = '/The (.*) value should be blank, (.*) given\./';

        yield 'true' => [new Blank(), true, $exception, $message];

        yield 'string' => [new Blank(), 'string', $exception, $message];
        yield 'whitespace string' => [new Blank(), ' ', $exception, $message];
        yield 'zero string' => [new Blank(), '0', $exception, $message];

        yield 'array' => [new Blank(), ['string'], $exception, $message];
        yield 'blank string array' => [new Blank(), [''], $exception, $message];
        yield 'whitespace array' => [new Blank(), [' '], $exception, $message];
        yield 'zero array' => [new Blank(), [0], $exception, $message];

        yield 'number' => [new Blank(), 10, $exception, $message];
        yield 'zero number' => [new Blank(), 0, $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'null' => [new Blank(), null];
        yield 'false' => [new Blank(), false];
        yield 'blank string' => [new Blank(), ''];
        yield 'blank array' => [new Blank(), []];

        yield 'normalizer whitespace' => [new Blank(normalizer: 'trim'), ' '];
        yield 'normalizer whitespace function' => [new Blank(normalizer: fn($value) => trim($value)), ' '];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Blank(message: '{{ name }} | {{ value }}'),
            'string',
            'test | "string"'
        ];
    }
}