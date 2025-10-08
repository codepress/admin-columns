<?php

namespace AC\Table;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\ListScreenId;
use AC\Type\TableId;

class TablePreference
{

    public function storage(): Preference
    {
        return (new SiteFactory())->create('layout_table');
    }

    public function get_list_id(TableId $table_id): ?ListScreenId
    {
        $list_id = $this->storage()->find((string)$table_id);

        return ListScreenId::is_valid_id($list_id)
            ? new ListScreenId($list_id)
            : null;
    }

    public function save(TableId $table_id, ListScreenId $id): void
    {
        $this->storage()->save((string)$table_id, (string)$id);
    }

}