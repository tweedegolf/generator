<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Exception\ActionFailedException;

class PrependAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'prepend';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $file = $builder->target($args[0]);
        $params = $args->get(2, false);
        if (is_array($params) || $params === true) {
            /** @var TemplateAction $templateAction */
            $templateAction = $builder->getAction('template');
            $template = $builder->source($args[1]);
            $string = $templateAction->getTemplateContent($template, is_array($params) ? $params : []);
        } else {
            $string = $args[1];
        }

        if (!file_exists($file)) {
            throw new ActionFailedException("Cannot append to non-existing file {$file}");
        }

        $content = file_get_contents($file);
        $content = $string . $content;
        if (!$simulate) {
            file_put_contents($file, $content);
        }
        $builder->did('prepend', $args[0]);
    }
}
