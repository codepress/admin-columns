<?php

namespace AC\Plugin;

use AC;

final class InstallCollection extends AC\Iterator
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    protected function add(Install $install): void
    {
        $this->data[] = $install;
    }

    /**
     * @return Install
     */
    public function current()
    {
        return parent::current();
    }

}