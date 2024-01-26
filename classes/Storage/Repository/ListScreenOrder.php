<?php

namespace AC\Storage\Repository;

use AC\Type\ListKey;
use AC\Type\ListScreenId;

class ListScreenOrder
{

    private const KEY = 'ac_list_screens_order';

    public function get(ListKey $key): array
    {
        $orders = $this->get_data();

        return $orders[(string)$key] ?? [];
    }

    public function set(ListKey $key, array $list_screen_ids): void
    {
        $data = $this->get_data();

        $data[(string)$key] = $list_screen_ids;

        update_option(self::KEY, $data, false);
    }

    public function add(ListKey $key, ListScreenId $id): void
    {
        $ids = $this->get($key);

        array_unshift($ids, (string)$id);

        $this->set($key, $ids);
    }

    private function get_data(): array
    {
        return get_option(self::KEY, []) ?: [];
    }

}