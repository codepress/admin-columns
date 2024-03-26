<?php

declare(strict_types=1);

namespace AC;

class ColumnCollection implements ColumnIterator
{

    /**
     * @var Column[]
     */
    private $data = [];

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Column $column): void
    {
        $this->data[] = $column;
    }

    public function current(): Column
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

    public function count(): int
    {
        return count($this->data);
    }

    public function first(): ?Column
    {
        return reset($this->data) ?: null;
    }

}