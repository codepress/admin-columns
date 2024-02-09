<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;
use Iterator;
use ReturnTypeWillChange;

abstract class Collection implements Iterator, Countable
{

    protected $data = [];

    #[ReturnTypeWillChange]
    public function current()
    {
        return current($this->data);
    }

    #[ReturnTypeWillChange]
    public function next(): void
    {
        next($this->data);
    }

    #[ReturnTypeWillChange]
    public function key(): int
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    #[ReturnTypeWillChange]
    public function rewind(): void
    {
        reset($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

}