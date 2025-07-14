<?php

namespace AC;

// TODO David remove this class in favor of the general collection
abstract class Iterator implements \Iterator
{

    protected array $data = [];

    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->data);
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        return next($this->data);
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->data);
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->key() !== null;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->data);
    }

}