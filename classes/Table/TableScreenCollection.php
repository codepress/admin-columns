<?php

declare(strict_types=1);

namespace AC\Table;

use AC\TableScreen;
use ArrayAccess;
use Countable;
use Iterator;

final class TableScreenCollection implements Iterator, Countable, ArrayAccess
{

    /**
     * @var TableScreen[]
     */
    private $data = [];

    public function __construct(array $table_screens = [])
    {
        array_map([$this, 'add'], $table_screens);
    }

    public function add(TableScreen $table_screen): void
    {
        $this->data[] = $table_screen;
    }

    public function current(): TableScreen
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

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

}