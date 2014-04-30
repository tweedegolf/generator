<?php

namespace TweedeGolf\Generator\Util;

class StringUtil
{
    /**
     * Replace all substrings that match $from with $to in the given range from $content.
     * @param string    $from       The string that should be replaced.
     * @param string    $to         The replacement string.
     * @param string    $content    The content on which the matching should be done.
     * @param int       $offset     First match to replace.
     * @param int       $limit      Number of matches to be replaced, or -1 if al should be replaced.
     * @return string
     */
    public static function stringReplace($from, $to, $content, $offset = 0, $limit = -1)
    {
        $fromLength = strlen($from);
        if ($limit === 0 || $fromLength < 1) {
            return $content;
        }
        $end = $offset + $limit;
        $lastIndex = 0;

        $result = "";
        $match = 0;


        while (true) {
            $index = strpos($content, $from, $lastIndex);
            if ($index === false) {
                break;
            }

            $result .= substr($content, $lastIndex, $index - $lastIndex);
            if ($match >= $offset && ($limit <= 0 || $match < $end)) {
                $result .= $to;
            } else {
                $result .= $from;
            }
            $lastIndex = $index + $fromLength;
            $match += 1;
        }
        $result .= substr($content, $lastIndex);
        return $result;
    }

    /**
     * Replace all matches that match the regular expression.
     * All matches with the regular expression $from will be replaced with the replacement string
     * $to, unless they are before $offset. Only $limit items will be replaced, or all if -1 is given.
     * @param string    $from       The regular expression for matching.
     * @param string    $to         The replacement string.
     * @param string    $content    The content on which the matching should be done.
     * @param int       $offset     First match to replace.
     * @param int       $limit      Number of matches to be replaced, or -1 if al should be replaced.
     * @return string
     */
    public static function regexReplace($from, $to, $content, $offset = 0, $limit = -1)
    {
        $match = 0;
        $end = $offset + $limit;
        $result = @preg_replace_callback($from, function (array $matches) use (&$match, $from, $to, $offset, $limit, $end) {
            $found = $matches[0];
            if ($match >= $offset && ($limit <= 0 || $match < $end)) {
                $found = preg_replace($from, $to, $found);
            }
            $match += 1;
            return $found;
        }, $content);

        if ($result === null) {
            $error = preg_last_error();
            throw new \BadMethodCallException("Regular expression error: {$error}");
        }
        return $result;
    }
}
