<?php

namespace AC\Admin\Preference;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\ListScreenId;
use AC\Type\TableId;

class ListScreen
{

    private Preference $storage;

    public function __construct(bool $is_network = false)
    {
        $this->storage = (new SiteFactory())->create(
            $is_network
                ? 'network_settings'
                : 'settings'
        );
    }

    public function get_last_visited_table(): ?TableId
    {
        $list_key = (string)$this->storage->find('last_visited_list_key');

        return TableId::validate($list_key)
            ? new TableId($list_key)
            : null;
    }

    public function set_last_visited_table(TableId $table_id): void
    {
        $this->storage->save(
            'last_visited_list_key',
            (string)$table_id
        );
    }

    public function set_list_id(TableId $table_id, ListScreenId $list_id): void
    {
        $this->storage->save(
            (string)$table_id,
            (string)$list_id
        );
    }

    public function get_list_id(TableId $table_id): ?ListScreenId
    {
        $list_id = (string)$this->storage->find((string)$table_id);

        return ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : null;
    }

}