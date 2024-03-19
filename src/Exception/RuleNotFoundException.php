<?php

namespace ProgrammatorDev\Validator\Exception;

class RuleNotFoundException extends \Exception
{
    protected $message = 'Rule does not exist.';
}