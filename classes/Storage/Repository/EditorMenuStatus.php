<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Storage\UserOption;

class EditorMenuStatus
{

    private UserOption $storage;

    public function __construct()
    {
        $this->storage = new UserOption('ac_preferences_menu_status');
    }

    public function get_groups(): array
    {
        return $this->storage->get() ?: [];
    }

    public function save_status(string $group, bool $active): void
    {
        $active
            ? $this->add_group($group)
            : $this->remove($group);
    }

    private function add_group(string $group): void
    {
        $data = $this->get_groups();

        if ( ! in_array($group, $data, true)) {
            $data[] = $group;
        }

        $this->save($data);
    }

    private function save(array $data): void
    {
        $data
            ? $this->storage->save($data)
            : $this->storage->delete();
    }

    private function remove(string $group): void
    {
        $data = $this->get_groups();

        $key = array_search($group, $data);

        if (false !== $key) {
            unset($data[$key]);
        }

        $this->save(
            array_values($data)
        );
    }

}