<?php

namespace AC\Plugin;

use AC\Iterator;

final class UpdateCollection extends Iterator
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);

        $this->sort_by_version();
    }

    private function add(Update $update)
    {
        $this->data[] = $update;
    }

    private function sort_by_version()
    {
        usort($this->data, static function (Update $a, Update $b) {
            return version_compare($a->get_version(), $b->get_version());
        });
    }

    /**
     * @return Update
     */
    public function current()
    {
        return parent::current();
    }

}