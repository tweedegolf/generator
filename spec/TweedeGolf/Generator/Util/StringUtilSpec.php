<?php

namespace spec\TweedeGolf\Generator\Util;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringUtilSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TweedeGolf\Generator\Util\StringUtil');
    }

    function its_stringReplace_should_replace_substrings()
    {
        self::stringReplace('hallo', 'hello', 'hallo world')->shouldReturn('hello world');
    }

    function its_stringReplace_should_replace_all_occurences()
    {
        self::stringReplace('hallo', 'hello', 'hallo world hallo')->shouldReturn('hello world hello');
    }

    function its_stringReplace_should_remove_all_occurences_if_the_replacement_is_empty()
    {
        self::stringReplace('abc', '', 'abcdefgabcdefg')->shouldReturn('defgdefg');
    }

    function its_stringReplace_should_replace_starting_at_offset()
    {
        self::stringReplace('hallo', 'hello', 'hallo hallo hallo hallo', 2)->shouldReturn('hallo hallo hello hello');
    }

    function its_stringReplace_should_replace_only_the_indicated_range()
    {
        self::stringReplace('hallo', 'hello', 'hallo hallo hallo', 1, 1)->shouldReturn('hallo hello hallo');
    }

    function its_stringReplace_should_not_replace_anything_when_an_empty_string_is_replaced()
    {
        self::stringReplace('', 'test', 'this is an example')->shouldReturn('this is an example');
    }

    function its_stringReplace_should_not_replace_inside_the_to_argument()
    {
        self::stringReplace('a', 'ab', 'aaa')->shouldReturn('ababab');
    }

    function its_regexReplace_should_replace_using_regular_expressions()
    {
        self::regexReplace('/[ab]/', 'z', 'abcd')->shouldReturn('zzcd');
    }

    function its_regexReplace_should_fail_with_an_invalid_regex()
    {
        self::shouldThrow('\BadMethodCallException')->duringRegexReplace('/thisisnot(aregex]', 'false', 'abcdef');
    }

    function its_regexReplace_should_replace_starting_at_offset()
    {
        self::regexReplace('/[ab]+/', 'z', 'aaaa ba bbb aaa', 2)->shouldReturn('aaaa ba z z');
    }

    function its_regexReplace_should_replace_only_the_indicated_range()
    {
        self::regexReplace('/hello|hallo/', 'hi', 'hello hallo hello hallo', 2, 1)
            ->shouldReturn('hello hallo hi hallo');
    }

    function its_regexReplace_should_remove_the_match_for_empty_replacements()
    {
        self::regexReplace('/\s*(hello|hallo)\s*/', '', 'hello world')->shouldReturn('world');
    }

    function its_regexReplace_should_not_replace_inside_the_replacement()
    {
        self::regexReplace('/[ab]{2}/', 'aaa', 'bbbb')->shouldReturn('aaaaaa');
    }

    function its_regexReplace_should_handle_backreferences_correctly()
    {
        self::regexReplace('/(a)(b)/', '\2$1', 'ab')->shouldReturn('ba');
    }
}
