<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\ListScreenId;
use AC\Type\Url\ListTable;

class Taxonomy extends ListTable
{

    public function __construct(string $taxonomy, ListScreenId $id = null, string $post_type = null)
    {
        parent::__construct('edit-tags.php', $id);

        $this->add_arg('taxonomy', $taxonomy);

        if ($post_type) {
            $this->add_arg('post_type', $post_type);
        }
    }
}