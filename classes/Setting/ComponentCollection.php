<?php

declare(strict_types=1);

namespace AC\Setting;

final class ComponentCollection extends Collection
{

    public function __construct(array $items = [])
    {
        array_map([$this, 'add'], $items);
    }

    public function add(Component $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Component
    {
        return parent::current();
    }

}