<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Exception\ActionFailedException;

class TemplateAction implements ActionInterface
{
    public function getName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate)
    {
        $source = $builder->source($args[0]);
        $target = $builder->target($args[1]);
        $variables = $args->get(2, array());
        $mode = $args->get(3, 0644);

        $directory = dirname($target);
        if (!is_dir($directory)) {
            $builder->exec('mkdir', [$directory, 0755, true]);
        }

        if (file_exists($target)) {
            throw new ActionFailedException("Target {$target} already exists");
        }

        $content = $this->getTemplateContent($source, $variables);
        if (!$simulate) {
            file_put_contents($target, $content);
            chmod($target, $mode);
        }
        $builder->did('generate', $args[1], [$mode]);
    }

    /**
     * Return the rendered content of a template.
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function getTemplateContent($template, array $variables)
    {
        $dir = dirname($template);
        $file = basename($template);

        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(array($dir)), array(
            'debug'            => true,
            'cache'            => false,
            'strict_variables' => true,
            'autoescape'       => false,
        ));
        return $twig->render($file, $variables);
    }
}
