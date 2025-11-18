<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Collection;
use AC\TableScreen;

final class TableScreenCollection extends Collection
{

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

    public function last(): ?TableScreen
    {
        return parent::last();
    }

}