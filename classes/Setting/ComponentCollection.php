<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;

class ComponentCollection extends Collection implements Countable
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Component $component): void
    {
        $this->data[] = $component;
    }

    public function current(): Component
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }

}