<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Settings\Column;
use Countable;

final class SettingCollection extends Collection implements Countable
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Column $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Column
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }
}