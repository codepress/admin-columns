<?php

declare(strict_types=1);

namespace AC\Deprecated;

use AC\Collection;

class HookCollection extends Collection
{

    public function __construct(array $hooks)
    {
        array_map([$this, 'add'], $hooks);
    }

    public function add(Hook $hook): void
    {
        $this->data[] = $hook;
    }

    public function current(): Hook
    {
        return current($this->data);
    }

}