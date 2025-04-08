<?php

declare(strict_types=1);

namespace AC;

use AC\Type\TableId;
use Countable;
use Iterator;

final class TableIdCollection implements Iterator, Countable
{

    /**
     * @var TableId[]
     */
    private array $data = [];

    public function __construct(array $ids = [])
    {
        array_map([$this, 'add'], $ids);
    }

    public function contains(TableId $id): bool
    {
        return null !== $this->search($id);
    }

    private function search(TableId $id): ?int
    {
        foreach ($this->data as $index => $table_id) {
            if ($table_id->equals($id)) {
                return $index;
            }
        }

        return null;
    }

    public function add(TableId $id): void
    {
        $this->data[] = $id;
    }

    public function current(): TableId
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