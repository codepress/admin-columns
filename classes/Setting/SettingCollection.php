<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;
use Iterator;

final class SettingCollection implements Iterator, Countable
{

    private $data = [];

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Setting $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Setting
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