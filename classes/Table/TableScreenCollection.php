<?php

declare(strict_types=1);

namespace AC\Table;

use AC\TableScreen;
use AC\Type\ListKey;
use Countable;
use InvalidArgumentException;
use Iterator;

final class TableScreenCollection implements Iterator, Countable
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
        $this->data[(string)$table_screen->get_key()] = $table_screen;
    }

    public function remove(ListKey $key): void
    {
        unset($this->data[(string)$key]);
    }

    public function contains(ListKey $key): bool
    {
        return isset($this->data[(string)$key]);
    }

    public function get(ListKey $listKey): TableScreen
    {
        if ( ! $this->contains($listKey)) {
            throw new InvalidArgumentException(sprintf('No segment found for key %s.', $listKey));
        }

        return $this->data[(string)$listKey];
    }

    public function current(): TableScreen
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