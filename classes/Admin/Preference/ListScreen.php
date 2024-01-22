<?php

namespace AC\Admin\Preference;

use AC\Preferences\Preference;
use AC\Storage\UserOption;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use Exception;

class ListScreen extends Preference
{

    private const OPTION_LAST_VISITED = 'last_visited_list_key';

    public function __construct(bool $is_network = false)
    {
        parent::__construct(
            new UserOption(
                $is_network
                    ? 'network_settings'
                    : 'settings'
            )
        );
    }

    public function get_last_visited_list_key(): ?ListKey
    {
        try {
            $list_key = new ListKey((string)$this->find(self::OPTION_LAST_VISITED));
        } catch (Exception $e) {
            return null;
        }

        return $list_key;
    }

    public function set_last_visited_list_key(ListKey $list_key): void
    {
        $this->save(
            self::OPTION_LAST_VISITED,
            (string)$list_key
        );
    }

    public function set_list_id(ListKey $list_key, ListScreenId $list_id): void
    {
        $this->save(
            (string)$list_key,
            (string)$list_id
        );
    }

    public function get_list_id(ListKey $list_key): ?ListScreenId
    {
        try {
            $list_id = new ListScreenId((string)$this->find((string)$list_key));
        } catch (Exception $e) {
            return null;
        }

        return $list_id;
    }

}