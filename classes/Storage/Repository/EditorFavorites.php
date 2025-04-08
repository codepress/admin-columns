<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Storage\UserOption;
use AC\TableIdCollection;
use AC\Type\TableId;

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

    public function add(TableId $id): void
    {
        $data = $this->get_data();

        if (in_array((string)$id, $data, true)) {
            return;
        }

        $data[] = (string)$id;

        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    public function remove(TableId $id): void
    {
        $data = $this->get_data();

        foreach ($data as $k => $value) {
            if ($id->equals(new TableId($value))) {
                unset($data[$k]);
            }
        }

        $this->storage->save(
            array_values($data)
        );
    }

    public function find_all(): TableIdCollection
    {
        $keys = new TableIdCollection();

        foreach ($this->get_data() as $key) {
            $keys->add(new TableId($key));
        }

        return $keys;
    }

}