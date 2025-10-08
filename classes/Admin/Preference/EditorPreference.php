<?php

namespace AC\Admin\Preference;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\ListScreenId;
use AC\Type\TableId;

class EditorPreference
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

    public function save(TableId $table_id, ListScreenId $list_id): void
    {
        $this->set_table_screen_id($table_id);
        $this->set_list_id($table_id, $list_id);
    }

    public function get_table_id(): ?TableId
    {
        $list_key = (string)$this->storage->find('last_visited_list_key');

        return TableId::validate($list_key)
            ? new TableId($list_key)
            : null;
    }

    public function get_list_id(TableId $table_id): ?ListScreenId
    {
        $list_id = (string)$this->storage->find((string)$table_id);

        return ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : null;
    }

    private function set_table_screen_id(TableId $table_id): void
    {
        $this->storage->save(
            'last_visited_list_key',
            (string)$table_id
        );
    }

    private function set_list_id(TableId $table_id, ListScreenId $list_id): void
    {
        $this->storage->save(
            (string)$table_id,
            (string)$list_id
        );
    }

}