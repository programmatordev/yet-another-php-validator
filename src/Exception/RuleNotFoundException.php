<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Exception;

class RuleNotFoundException extends \Exception
{
    protected $message = 'Rule does not exist.';
}