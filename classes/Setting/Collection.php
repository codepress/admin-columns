<?php

declare(strict_types=1);

namespace AC\Setting;

use Iterator;

abstract class Collection implements Iterator
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

}