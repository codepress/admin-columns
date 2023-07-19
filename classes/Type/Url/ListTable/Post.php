<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\ListScreenId;
use AC\Type\Url\ListTable;

class Post extends ListTable
{

    public function __construct(string $post_type, ListScreenId $list_id = null)
    {
        parent::__construct('edit.php', $list_id);

        $this->add_one('post_type', $post_type);
    }

}