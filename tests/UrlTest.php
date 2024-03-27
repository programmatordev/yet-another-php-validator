<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\UrlException;
use ProgrammatorDev\Validator\Rule\Url;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class UrlTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Expected value of type "string", "(.*)" given\./';

        yield 'invalid type' => [new Url(), 1, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = UrlException::class;
        $message = '/The (.*) value is not a valid URL address, (.*) given\./';

        yield 'invalid url' => [new URL(), 'invalid', $exception, $message];
        yield 'unallowed protocol' => [new URL(protocols: ['https']), 'http://test.com', $exception, $message];
        yield 'unallowed relative protocol' => [new URL(), '//test.com', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'domain' => [new URL(), 'https://test.com'];
        yield 'multi-level domain' => [new URL(), 'https://multi.level.url.test.com'];
        yield 'chars' => [new URL(), 'https://テスト.com'];
        yield 'punycode' => [new URL(), 'https://xn--zckzah.com'];
        yield 'port' => [new URL(), 'https://test.com:8000'];
        yield 'path' => [new URL(), 'https://test.com/path'];
        yield 'query' => [new URL(), 'https://test.com?test=1'];
        yield 'fragment' => [new URL(), 'https://test.com#test'];
        yield 'ipv4' => [new URL(), 'https://127.0.0.1'];
        yield 'ipv6' => [new URL(), 'https://[ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff]'];
        yield 'basic auth' => [new URL(), 'https://username:password@test.com'];
        yield 'full domain' => [new URL(), 'https://username:password@test.com:8000/path?test=1#test'];
        yield 'full ipv4' => [new URL(), 'https://username:password@127.0.0.1:8000/path?test=1#test'];
        yield 'full ipv6' => [new URL(), 'https://username:password@[ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff]:8000/path?test=1#test'];
        yield 'custom protocol' => [new URL(protocols: ['ftp']), 'ftp://test.com'];
        yield 'allow relative protocol with protocol' => [new URL(allowRelativeProtocol: true), 'https://test.com'];
        yield 'allow relative protocol without protocol' => [new URL(allowRelativeProtocol: true), '//test.com'];
        yield 'allow relative protocol only' => [new URL(protocols: [], allowRelativeProtocol: true), '//test.com'];
        yield 'normalizer' => [new URL(normalizer: 'trim'), 'https://test.com '];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Url(
                message: 'The {{ name }} value {{ value }} is not a valid URL address. Allowed protocols: {{ protocols }}.'
            ),
            'invalid',
            'The test value "invalid" is not a valid URL address. Allowed protocols: ["http", "https"].'
        ];
    }
}