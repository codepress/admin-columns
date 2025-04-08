<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\ListScreenId;
use AC\Type\TableId;

class EditorColumns extends Editor
{

    public function __construct(TableId $table_id, ListScreenId $list_id = null)
    {
        parent::__construct('columns');

        $this->add('list_screen', (string)$table_id);

        if ($list_id) {
            $this->add('layout_id', (string)$list_id);
        }
    }

}