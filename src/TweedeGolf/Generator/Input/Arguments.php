<?php

namespace TweedeGolf\Generator\Input;

use TweedeGolf\Generator\Exception\ArgumentNotFoundException;

class Arguments implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $interactive;

    public function __construct(array $arguments, $forceInteractive = false)
    {
        $this->data = $arguments;
        $this->interactive = $forceInteractive;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        throw new ArgumentNotFoundException("Argument '{$offset}' not found");
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }
        return $default;
    }

    /**
     * Whether or not the arguments were retrieved interactively or not.
     * @return bool
     */
    public function isForcedInteractive()
    {
        return $this->interactive;
    }

    /**
     * Retrieve the internal data array.
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param string $key
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    public function __call($method, array $arguments)
    {
        if (strlen($method) > 3 && substr($method, 0, 3) === 'get') {
            $method = substr($method, 3);
        }
        return $this->offsetGet($method);
    }
}
