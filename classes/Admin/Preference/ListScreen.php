<?php

namespace AC\Admin\Preference;

use AC\Preferences\SiteFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;

class ListScreen
{

    private $storage;

    public function __construct(bool $is_network = false)
    {
        $this->storage = (new SiteFactory())->create(
            $is_network
                ? 'network_settings'
                : 'settings'
        );
    }

    public function get_last_visited_list_key(): ?ListKey
    {
        $list_key = (string)$this->storage->find('last_visited_list_key');

        return ListKey::validate($list_key)
            ? new ListKey($list_key)
            : null;
    }

    public function set_last_visited_list_key(ListKey $list_key): void
    {
        $this->storage->save(
            'last_visited_list_key',
            (string)$list_key
        );
    }

    public function set_list_id(ListKey $list_key, ListScreenId $list_id): void
    {
        $this->storage->save(
            (string)$list_key,
            (string)$list_id
        );
    }

    public function get_list_id(ListKey $list_key): ?ListScreenId
    {
        $list_id = (string)$this->storage->find((string)$list_key);

        return ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : null;
    }

}