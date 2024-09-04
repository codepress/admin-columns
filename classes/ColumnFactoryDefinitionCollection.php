<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ColumnFactoryDefinition;
use Countable;
use Iterator;

final class ColumnFactoryDefinitionCollection implements Iterator, Countable
{

    private $data = [];

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(ColumnFactoryDefinition $definition): void
    {
        $this->data[] = $definition;
    }

    public function current(): ColumnFactoryDefinition
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