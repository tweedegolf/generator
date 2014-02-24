<?php

namespace TweedeGolf\Generator\Util;

class InputFieldHelper
{
    public static function getTitleForFieldname($field)
    {
        return ucfirst(str_replace('-', ' ', $field));
    }
}
