<?php

declare(strict_types=1);

namespace AC;

use AC\Exception\IteratorException;

class ColumnCollection implements ColumnIterator
{

    /**
     * @var Column[]
     */
    private array $data = [];

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Column $column): self
    {
        $this->data[] = $column;

        return $this;
    }

    public static function from_iterator(ColumnIterator $iterator): self
    {
        return new self(iterator_to_array($iterator));
    }

    public function current(): Column
    {
        $current = current($this->data);

        if ( ! $current instanceof Column) {
            throw IteratorException::from_current();
        }

        return $current;
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): int
    {
        $key = key($this->data);

        if ( ! is_int($key)) {
            throw IteratorException::from_key();
        }

        return $key;
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
        $first = reset($this->data);

        return $first instanceof Column
            ? $first
            : null;
    }

}
