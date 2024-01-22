<?php

declare(strict_types=1);

namespace AC\Storage\Model;

use AC\Preferences\Preference;
use AC\Storage\UserOption;

class EditorMenuStatus
{

    private $storage;

    public function __construct()
    {
        $this->storage = new Preference(new UserOption('menu_status'));
    }

    public function save_status(string $group, bool $active): void
    {
        $active
            ? $this->storage->save($group, '1')
            : $this->storage->delete($group);
    }

    public function find_all_active_groups(): array
    {
        $groups = $this->storage->find_all() ?: [];

        return array_keys($groups);
    }

}