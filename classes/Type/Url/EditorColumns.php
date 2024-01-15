<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\ListKey;
use AC\Type\ListScreenId;

class EditorColumns extends Editor
{

    public function __construct(ListKey $list_key, ListScreenId $list_id = null)
    {
        parent::__construct('columns');

        $this->add_arg('list_screen', (string)$list_key);

        if ($list_id) {
            $this->add_arg('layout_id', (string)$list_id);
        }
    }

}