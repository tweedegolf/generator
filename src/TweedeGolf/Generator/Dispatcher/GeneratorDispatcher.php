<?php

namespace TweedeGolf\Generator\Dispatcher;

use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Registry\GeneratorRegistryInterface;

class GeneratorDispatcher implements GeneratorDispatcherInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var GeneratorRegistryInterface
     */
    private $registry;

    public function __construct(BuilderInterface $builder, GeneratorRegistryInterface $registry)
    {
        $this->builder = $builder;
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($generator, $arguments, $path = null, $simulate = false)
    {
        if (is_string($generator)) {
            $generator = $this->registry->getGenerator($generator);
        }

        $builder = $this->builder->forGenerator($generator);
        if ($path !== null) {
            $builder = $builder->withPath($path);
        }
        $builder->simulated($simulate);
        $generator->generate($builder, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistry()
    {
        return $this->registry;
    }
}
