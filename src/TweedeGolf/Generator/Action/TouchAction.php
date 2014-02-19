<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;

class TouchAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'touch';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $file = $builder->target($args[0]);
        $mode = $args->get(1, 0644);
        if (!file_exists($file)) {
            $builder->exec('create', [$file, $mode]);
        } else {
            if (!$simulate) {
                touch($file);
            }
            $builder->did('touch', $args[0]);
        }
    }
}
