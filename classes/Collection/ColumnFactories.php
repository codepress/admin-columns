<?php

declare(strict_types=1);

namespace AC\Collection;

use AC\Collection;
use AC\Column\ColumnFactory;

class ColumnFactories extends Collection
{

    public function __construct(array $factories = [])
    {
        array_map([$this, 'add'], $factories);
    }

    public function add(ColumnFactory $factory): void
    {
        $this->data[] = $factory;
    }

    public function current(): ColumnFactory
    {
        return current($this->data);
    }
}