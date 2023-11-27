<?php

declare(strict_types=1);

namespace AC;

use Countable;
use InvalidArgumentException;
use Iterator;

class ColumnCollection implements Iterator, Countable
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
        $this->data[$column->get_name()] = $column;
    }

    public function remove(string $name): void
    {
        unset($this->data[$name]);
    }

    public function contains(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function get(string $name): Column
    {
        if ( ! $this->contains($name)) {
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

    public function get_keys(): array
    {
        return array_keys($this->data);
    }

}