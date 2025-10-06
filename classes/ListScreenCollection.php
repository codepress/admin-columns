<?php

declare(strict_types=1);

namespace AC;

use Countable;
use Iterator;

final class ListScreenCollection implements Iterator, Countable
{

    /**
     * @var ListScreen[]
     */
    private array $data = [];

    public function __construct(array $list_screens = [])
    {
        array_map([$this, 'add'], $list_screens);
    }

    public function add(ListScreen $list_screen): void
    {
        $this->data[(string)$list_screen->get_id()] = $list_screen;
    }

    public function remove(ListScreen $list_screen): void
    {
        unset($this->data[(string)$list_screen->get_id()]);
    }

    public function contains(ListScreen $list_screen): bool
    {
        return isset($this->data[(string)$list_screen->get_id()]);
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function current(): ListScreen
    {
        return current($this->data);
    }

    public function first(): ?ListScreen
    {
        return $this->count()
            ? $this->data[array_key_first($this->data)]
            : null;
    }

    public function key(): string
    {
        return key($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function get_copy(): array
    {
        $copy = $this->data;

        reset($copy);

        return $copy;
    }

}