<?php

declare(strict_types=1);

namespace AC\Storage\Model;

use AC\Preferences\Preference;
use AC\Storage\UserOption;
use AC\Type\ListKey;

class EditorFavorites
{

    private $storage;

    public function __construct()
    {
        $this->storage = new Preference(new UserOption('menu_favorites'));
    }

    public function set_as_favorite(ListKey $key): void
    {
        $this->storage->save((string)$key, '1');
    }

    public function remove_as_favorite(ListKey $key): void
    {
        $this->storage->delete((string)$key);
    }

    public function find_all_favorites(): array
    {
        $list_keys = $this->storage->find_all() ?: [];

        return array_keys($list_keys);
    }

}