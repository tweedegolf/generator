<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Exception\ActionFailedException;

class ModifyAction implements ActionInterface
{
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
        $file = $builder->target($args[0]);
        $modifier = $args[1];

        if (!is_callable($modifier)) {
            throw new ActionFailedException("Modifier parameter should be a callable");
        }

        if (!file_exists($file)) {
            throw new ActionFailedException("File {$file} does not exist, cannot modify");
        }
        $contents = file_get_contents($file);

        $modified = $modifier($contents);
        if (!$simulate && is_string($modified)) {
            file_put_contents($file, $modified);
        }

        $builder->did('modify', $args[0]);
    }
}
