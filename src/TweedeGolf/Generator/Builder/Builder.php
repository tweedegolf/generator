<?php

namespace TweedeGolf\Generator\Builder;

use TweedeGolf\Generator\Action\AppendAction;
use TweedeGolf\Generator\Action\CreateAction;
use TweedeGolf\Generator\Action\MkdirAction;
use TweedeGolf\Generator\Action\ModifyAction;
use TweedeGolf\Generator\Action\PrependAction;
use TweedeGolf\Generator\Action\ReplaceAction;
use TweedeGolf\Generator\Action\TemplateAction;
use TweedeGolf\Generator\Action\TouchAction;

class Builder extends AbstractBuilder
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultActions()
    {
        return [
            new AppendAction(),
            new CreateAction(),
            new MkdirAction(),
            new ModifyAction(),
            new PrependAction(),
            new ReplaceAction(),
            new TemplateAction(),
            new TouchAction(),
        ];
    }
}
