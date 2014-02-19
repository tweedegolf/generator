<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;

class ModifyAction implements ActionInterface
{
    const AFTER = 'after';
    const BEFORE = 'before';
    const PREPEND = 'prepend';
    const APPEND = 'append';
    const REGEX = 'regex';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'modify';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {

        // TODO: Implement ModifyAction::execute() method.

        $builder->exec('modify', [
            [ModifyAction::AFTER . ModifyAction::REGEX, 'Symfony\\'], [ModifyAction::BEFORE, ''], []
        ]);
    }
}
