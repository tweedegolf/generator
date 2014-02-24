<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

class ChoiceType extends AbstractInputType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'choice';
    }

    /**
     * Ask for some input
     * @param array $options
     * @return mixed
     */
    public function ask(array $options, OutputInterface $output, HelperSet $helperSet)
    {
        // TODO: Implement ask() method.
    }
}
