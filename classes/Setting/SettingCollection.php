<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;

final class SettingCollection extends Collection implements Countable
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Setting $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Setting
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }
}