<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RuleNotFoundException;
use ProgrammatorDev\YetAnotherPhpValidator\Factory\Factory;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class FactoryTest extends AbstractTest
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    public function testFactoryCreateRuleFailure()
    {
        $this->expectException(RuleNotFoundException::class);
        $this->expectExceptionMessage('"invalidRule" rule does not exist.');
        $this->factory->createRule('invalidRule');
    }

    public function testFactoryCreateRuleSuccess()
    {
        $this->assertInstanceOf(RuleInterface::class, $this->factory->createRule('notBlank'));
    }
}