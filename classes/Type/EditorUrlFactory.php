<?php

namespace AC\Type;

use AC\Type\Url\EditorColumns;
use AC\Type\Url\EditorNetworkColumns;

class EditorUrlFactory
{

    public static function create(TableId $table_id, bool $is_network, ListScreenId $list_id = null): Uri
    {
        return $is_network
            ? new EditorNetworkColumns($table_id, $list_id)
            : new EditorColumns($table_id, $list_id);
    }

}