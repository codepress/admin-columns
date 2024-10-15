<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\Url\ListTable;

class Post extends ListTable
{

    public function __construct(string $post_type)
    {
        parent::__construct('edit.php');

        $this->add_arg('post_type', $post_type);
    }

}