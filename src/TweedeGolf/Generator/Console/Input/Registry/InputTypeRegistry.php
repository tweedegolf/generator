<?php

namespace TweedeGolf\Generator\Console\Input\Registry;

use TweedeGolf\Generator\Console\Input;

class InputTypeRegistry extends AbstractInputTypeRegistry
{
    /**
     * {@inheritdoc}
     */
    public function addDefaultTypes()
    {
        $this->addType(new Input\BooleanType(), 'boolean');
        $this->addType(new Input\ChoiceType(), 'choice');
        $this->addType(new Input\StringType(), 'string');
    }
}
