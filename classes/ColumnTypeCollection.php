<?php

declare(strict_types=1);

namespace AC;

use Countable;
use InvalidArgumentException;
use Iterator;

class ColumnTypeCollection implements Iterator, Countable
{

    /**
     * @var Column[]
     */
    private $data = [];

    public function __construct(array $column_types = [])
    {
        array_map([$this, 'add'], $column_types);
    }

    public function add(Column $column_type): void
    {
        $this->data[] = $column_type;
    }

    public function remove(Column $column): void
    {
        $index = $this->search($column->get_type());

        if (null === $index) {
            throw new InvalidArgumentException('Index not found');
        }

        unset($this->data[$index]);
    }

    public function contains(Column $column): bool
    {
        return null !== $this->search($column->get_type());
    }

    private function search(string $type): ?int
    {
        foreach ($this->data as $i => $column) {
            if ($column->get_type() === $type) {
                return $i;
            }
        }

        return null;
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

}