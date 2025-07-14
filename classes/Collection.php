<?php

declare(strict_types=1);

namespace AC;

use Countable;
use Iterator;

abstract class Collection implements Iterator, Countable
{

    protected int $index = 0;

    protected array $data = [];

    public function next(): void
    {
        next($this->data);

        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return count($this->data) > $this->index;
    }

    public function rewind(): void
    {
        reset($this->data);

        $this->index = 0;
    }

    public function count(): int
    {
        return count($this->data);
    }

}