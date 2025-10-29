<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

final class TableIdCollection extends Collection
{

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

}