<?php

namespace AC\Table;

use AC\Preferences\SiteFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;

class LayoutPreference
{

    private $storage;

    public function __construct()
    {
        $this->storage = (new SiteFactory())->create('layout_table');
    }

    public function find_list_id(ListKey $key): ?ListScreenId
    {
        $list_id = $this->storage->find((string)$key);

        return ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : null;
    }

    public function save(ListKey $key, ListScreenId $id): void
    {
        $this->storage->save((string)$key, (string)$id);
    }

}