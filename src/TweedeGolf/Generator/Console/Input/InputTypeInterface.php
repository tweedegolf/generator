<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface InputTypeInterface
{
    /**
     * Retrieve the name for this input type.
     * @return string
     */
    public function getName();

    /**
     * Ask for some input
     * @param array $options
     * @return mixed
     */
    public function ask(array $options, OutputInterface $output, HelperSet $helperSet);
}
