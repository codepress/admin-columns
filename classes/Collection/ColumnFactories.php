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
        $this->data[$factory->get_column_type()] = $factory;
    }

    public function with_collection(ColumnFactories $factories): self
    {
        return new self(
            array_merge(
                $this->data,
                iterator_to_array($factories)
            )
        );
    }

    public function current(): ColumnFactory
    {
        return parent::current();
    }
}