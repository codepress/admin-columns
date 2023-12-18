<?php

declare(strict_types=1);

namespace AC;

use InvalidArgumentException;

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
        // TODO key needs to be an int
        $this->data[$column->get_name()] = $column;
    }

    public function exists(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function get(string $name): Column
    {
        if ( ! $this->exists($name)) {
            throw new InvalidArgumentException(sprintf('No column found for name %s.', $name));
        }

        return $this->data[$name];
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

    public function keys(): array
    {
        return array_keys($this->data);
    }

}