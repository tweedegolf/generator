<?php

namespace TweedeGolf\Generator\Console\Input;

use Symfony\Component\Console\Helper\DialogHelper;
use TweedeGolf\Generator\Console\Questioner;

class BooleanType extends AbstractInputType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function ask(array $options, Questioner $questioner)
    {
        $this->preamble($options, $questioner);

        /** @var DialogHelper $dialog */
        $dialog = $questioner->getHelper('dialog');
        return $dialog->askConfirmation($questioner->getOutput(), $this->getPrompt($options), $options['default']);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromptDefault(array $options)
    {
        if ($options['default'] === true) {
            return 'Y/n';
        }

        if ($options['default'] === false) {
            return 'y/N';
        }

        return 'y/n';
    }
}
