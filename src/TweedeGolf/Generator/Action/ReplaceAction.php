<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Exception\ActionFailedException;
use TweedeGolf\Generator\Util\PregErrorToString;
use TweedeGolf\Generator\Util\StringUtil;

class ReplaceAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'replace';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $file = $builder->target($args[0]);
        $from = $args[1];
        $to = $args[2];
        $regex = $args->get(3, false);
        $limit = $args->get(4, -1);
        $offset = $args->get(5, 0);

        if (!file_exists($file) || !is_readable($file)) {
            throw new ActionFailedException("File {$file} does not exist or is not readable.");
        }
        $content = file_get_contents($file);
        if ($regex) {
            $content = StringUtil::regexReplace($from, $to, $content, $offset, $limit);
        } else {
            $content = StringUtil::stringReplace($from, $to, $content, $offset, $limit);
        }

        if (!$simulate) {
            file_put_contents($file, $content);
        }
        $builder->did('replace', $args[0], [$from, $to]);
    }
}
