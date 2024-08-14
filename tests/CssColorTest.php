<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\CssColorException;
use ProgrammatorDev\Validator\Rule\CssColor;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class CssColorTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "formats" option is not valid\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", "(.*)" given\./';

        yield 'invalid option format' => [new CssColor(formats: ['invalid']), '#123456', $invalidOptionMessage];
        yield 'unexpected type' => [new CssColor(), 1, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = CssColorException::class;
        $message = '/The (.*) value is not a valid CSS color\./';

        yield 'format' => [new CssColor(), 'invalid', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'hex-long' => [new CssColor(formats: ['hex-long']), '#0f0f0f'];
        yield 'hex-long-with-alpha' => [new CssColor(formats: ['hex-long-with-alpha']), '#0f0f0f50'];
        yield 'hex-short' => [new CssColor(formats: ['hex-short']), '#0f0'];
        yield 'hex-short-with-alpha' => [new CssColor(formats: ['hex-short-with-alpha']), '#0f05'];
        yield 'basic-named-colors' => [new CssColor(formats: ['basic-named-colors']), 'black'];
        yield 'extended-names-colors' => [new CssColor(formats: ['extended-named-colors']), 'darkgoldenrod'];
        yield 'system-colors' => [new CssColor(formats: ['system-colors']), 'AccentColor'];
        yield 'keywords' => [new CssColor(formats: ['keywords']), 'transparent'];
        yield 'rgb' => [new CssColor(formats: ['rgb']), 'rgb(0, 255, 0)'];
        yield 'rgba' => [new CssColor(formats: ['rgba']), 'rgba(0, 255, 0, 0)'];
        yield 'hsl' => [new CssColor(formats: ['hsl']), 'hsl(0, 50%, 50%)'];
        yield 'hsla' => [new CssColor(formats: ['hsla']), 'hsla(0, 50%, 50%, 0)'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new CssColor(message: '{{ name }} | {{ value }} | {{ formats }}'),
            'invalid',
            'test | "invalid" | ["hex-long", "hex-long-with-alpha", "hex-short", "hex-short-with-alpha", "basic-named-colors", "extended-named-colors", "system-colors", "keywords", "rgb", "rgba", "hsl", "hsla"]'
        ];
    }
}