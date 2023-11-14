<?php

namespace AC\Type;

use AC\Type\Url\EditorColumns;
use AC\Type\Url\EditorNetworkColumns;

class EditorUrlFactory
{

    public static function create(ListKey $key, bool $is_network, ListScreenId $id = null): Uri
    {
        return $is_network
            ? new EditorNetworkColumns((string)$key, $id)
            : new EditorColumns((string)$key, $id);
    }

}