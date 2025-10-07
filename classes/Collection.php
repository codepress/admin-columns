<?php

declare(strict_types=1);

namespace AC;

use Countable;
use Iterator;

abstract class Collection implements Iterator, Countable
{

    private const FIRST_INDEX = 0;

    protected int $index = self::FIRST_INDEX;

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
        return $this->count() > $this->index;
    }

    public function rewind(): void
    {
        reset($this->data);

        $this->index = self::FIRST_INDEX;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function first()
    {
        return $this->count()
            ? $this->data[self::FIRST_INDEX]
            : null;
    }

}