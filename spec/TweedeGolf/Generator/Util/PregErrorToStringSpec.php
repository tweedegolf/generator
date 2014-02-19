<?php

namespace spec\TweedeGolf\Generator\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PregErrorToStringSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TweedeGolf\Generator\Util\PregErrorToString');
    }

    function it_should_give_the_correct_error_string()
    {
        self::getString(PREG_NO_ERROR)->shouldReturn('PREG_NO_ERROR');
        self::getString(PREG_BAD_UTF8_OFFSET_ERROR)->shouldReturn('PREG_BAD_UTF8_OFFSET_ERROR');
    }

    function it_should_fail_on_incorrect_error_numbers()
    {
        self::shouldThrow('\BadMethodCallException')->duringGetString(293);
    }

    function it_should_fail_on_incorrect_types()
    {
        self::shouldThrow('\BadMethodCallException')->duringGetString('test');
        self::shouldThrow('\BadMethodCallEXception')->duringGetString(false);
    }
}
