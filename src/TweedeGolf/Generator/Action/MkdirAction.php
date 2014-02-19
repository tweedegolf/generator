<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;

class MkdirAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mkdir';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $dir = $builder->target($args[0]);
        $mode = $args->get(1, 0777);
        if (!$simulate) {
            mkdir($dir, $mode, $args->get(2, true));
        }
        $builder->did('mkdir', $args[0], [$mode]);
    }
}
