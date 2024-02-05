<?php

declare(strict_types=1);

namespace AC\Collection;

use AC\Column\ColumnFactory;
use AC\Iterator;

class ColumnFactories extends Iterator
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
        return parent::current();
    }
}