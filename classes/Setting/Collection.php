<?php

declare(strict_types=1);

namespace AC\Setting;

use Iterator;

// TODO David decide on generic component or not
abstract class Collection implements Iterator
{

    protected $data = [];

    public function current()
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): int
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

}