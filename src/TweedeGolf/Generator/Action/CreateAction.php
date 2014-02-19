<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Exception\ActionFailedException;

class CreateAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'create';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $file = $builder->target($args[0]);
        $mode = $args->get(1, 0644);

        if (file_exists($file)) {
            throw new ActionFailedException("Cannot create {$file}, file already exists.");
        }

        if (!$simulate) {
            touch($file);
            chmod($file, $mode);
        }
        $builder->did('create', $args[0], [$mode]);
    }
}
