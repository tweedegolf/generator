<?php

namespace TweedeGolf\Generator\Util;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use TweedeGolf\Generator\GeneratorInterface;

class ValidationProblemFormatter
{
    public static function format(
        ConstraintViolationList $problems,
        GeneratorInterface $generator,
        FormatterHelper $formatter
    ) {
        $messages = [];

        // add header message
        $problemCount = count($problems);
        $name = $generator->getName();
        $messages[] = "While trying to call the generator '{$name}', {$problemCount} problems were found:";
        $messages[] = "";

        /** @var ConstraintViolation $problem */
        foreach ($problems as $problem) {
            $messages[] = "In \$arguments{$problem->getPropertyPath()}: {$problem->getMessage()}";
        }

        return $formatter->formatBlock($messages, 'error', true);
    }
}
