<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use TweedeGolf\Generator\Console\Questioner;

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
    public function ask(array $options, Questioner $questioner);

    /**
     * Return an array of default option values.
     * Values other than these will not be allowed by the questioner.
     * @return array
     */
    public function getDefaultOptions();
}
