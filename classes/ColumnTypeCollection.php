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

    public function add(Column $column): void
    {
        $this->data[$column->get_type()] = $column;
    }

    public function exists(string $type): bool
    {
        return isset($this->data[$type]);
    }

    public function get(string $type): Column
    {
        if ( ! $this->exists($type)) {
            throw new InvalidArgumentException(sprintf('No column found for type %s.', $type));
        }

        return $this->data[$type];
    }

    public function current(): Column
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): string
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