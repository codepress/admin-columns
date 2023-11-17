<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ArrayIterator;
use AC\TableScreen;

class TableScreens extends ArrayIterator
{

    public function __construct(array $table_screens = [])
    {
        parent::__construct();

        array_map([$this, 'add'], $table_screens);
    }

    public function add(TableScreen $table_screen): void
    {
        $this->array[] = $table_screen;
    }

    /**
     * @return TableScreen[]
     */
    public function all(): array
    {
        return $this->get_copy();
    }

}