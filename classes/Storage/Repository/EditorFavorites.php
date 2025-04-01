<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\ListKeyCollection;
use AC\Storage\UserOption;
use AC\Type\ListKey;

class EditorFavorites
{

    private UserOption $storage;

    public function __construct()
    {
        $this->storage = new UserOption('ac_preferences_menu_favorites');
    }

    private function get_data(): array
    {
        return $this->storage->get() ?: [];
    }

    public function add(ListKey $key): void
    {
        $data = $this->get_data();

        if (in_array((string)$key, $data, true)) {
            return;
        }

        $data[] = (string)$key;

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    public function remove(ListKey $key): void
    {
        $data = $this->get_data();

        foreach ($data as $k => $value) {
            if ($key->equals(new ListKey($value))) {
                unset($data[$k]);
            }
        }

        $this->storage->save(
            array_values($data)
        );
    }

    public function find_all(): ListKeyCollection
    {
        $keys = new ListKeyCollection();

        foreach ($this->get_data() as $key) {
            $keys->add(new ListKey($key));
        }

        return $keys;
    }

}