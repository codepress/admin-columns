<?php

declare(strict_types=1);

namespace AC\Deprecated;

use AC\Collection;
use Countable;

class HookCollection extends Collection implements Countable
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

    public function count(): int
    {
        return count($this->data);
    }

}