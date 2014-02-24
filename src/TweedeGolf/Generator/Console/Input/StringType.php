<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Helper\DialogHelper;
use TweedeGolf\Generator\Console\Questioner;

class StringType extends AbstractInputType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'string';
    }

    /**
     * {@inheritdoc}
     */
    public function ask(array $options, Questioner $questioner)
    {
        $this->preamble($options, $questioner);

        /** @var DialogHelper $dialog */
        $dialog = $questioner->getHelper('dialog');
        return $dialog->ask($questioner->getOutput(), $this->getPrompt($options), $options['default']);
    }
}
