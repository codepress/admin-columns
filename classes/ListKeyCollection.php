<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ListKey;
use Countable;
use Iterator;

final class ListKeyCollection implements Iterator, Countable
{

    /**
     * @var ListKey[]
     */
    private $data = [];

    public function __construct(array $keys = [])
    {
        array_map([$this, 'add'], $keys);
    }

    public function add(ListKey $key): void
    {
        $this->data[] = $key;
    }

    public function current(): ListKey
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