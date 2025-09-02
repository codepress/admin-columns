<?php

namespace AC\Plugin;

use AC;

final class InstallCollection extends AC\Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    protected function add(Install $install): void
    {
        $this->data[] = $install;
    }

    public function current(): Install
    {
        return current($this->data);
    }

}