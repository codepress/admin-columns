<?php

namespace AC\Plugin;

use AC\Collection;

final class UpdateCollection extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);

        $this->sort_by_version();
    }

    private function add(Update $update): void
    {
        $this->data[] = $update;
    }

    private function sort_by_version(): void
    {
        usort($this->data, static function (Update $a, Update $b) {
            return version_compare($a->get_version(), $b->get_version());
        });
    }

    public function current(): Update
    {
        return current($this->data);
    }

}