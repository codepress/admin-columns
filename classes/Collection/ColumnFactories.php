<?php

declare(strict_types=1);

namespace AC\Collection;

use AC\Column\ColumnFactory;
use AC\Iterator;

class ColumnFactories extends Iterator
{

    public function __construct(array $factories = [])
    {
        foreach ($factories as $type => $factory) {
            $this->add($type, $factory);
        }
    }

    // TODO Stefan remove string $type and let the $factory resolve it for you
    public function add(string $type, ColumnFactory $factory): void
    {
        $this->data[$type] = $factory;
    }

    public function current(): ColumnFactory
    {
        return parent::current();
    }
}