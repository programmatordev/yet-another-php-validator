<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\LocaleException;
use ProgrammatorDev\Validator\Rule\Locale;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class LocaleTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Expected value of type "string", (.*) given\./';

        yield 'invalid type' => [new Locale(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LocaleException::class;
        $message = '/The (.*) value is not a valid locale\./';

        yield 'invalid' => [new Locale(), 'invalid', $exception, $message];
        yield 'uncanonicalized 1' => [new Locale(), 'pt_pt', $exception, $message];
        yield 'uncanonicalized 2' => [new Locale(), 'pt-PT', $exception, $message];
        yield 'uncanonicalized 3' => [new Locale(), 'PT-pt.utf8', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'language code' => [new Locale(), 'pt'];
        yield 'language and country code' => [new Locale(), 'pt_PT'];
        yield 'canonicalized 1' => [new Locale(canonicalize: true), 'pt_pt'];
        yield 'canonicalized 2' => [new Locale(canonicalize: true), 'pt-PT'];
        yield 'canonicalized 3' => [new Locale(canonicalize: true), 'PT-pt.utf8'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Locale(
                message: '{{ name }} | {{ value }}'
            ),
            'invalid',
            'test | "invalid"'
        ];
    }
}