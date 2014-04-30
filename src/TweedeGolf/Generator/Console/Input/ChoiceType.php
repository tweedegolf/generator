<?php

namespace TweedeGolf\Generator\Console\Input;

use TweedeGolf\Generator\Console\Questioner;

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
     * {@inheritdoc}
     */
    public function ask(array $options, Questioner $questioner)
    {
        $this->preamble($options, $questioner);

        $prompt = $this->getPrompt($options);
        // TODO: Implement ask() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $options = parent::getDefaultOptions();
        return array_merge($options, [
            'choices' => [],
            'callback' => null,
            'autocomplete' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getPromptDefault(array $options)
    {
        return $options['default'];
    }
}
