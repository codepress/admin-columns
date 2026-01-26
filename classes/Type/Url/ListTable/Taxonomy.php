<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\Uri;

class Taxonomy extends Uri
{

    public function __construct(string $taxonomy, ?string $post_type = null)
    {
        parent::__construct((string)admin_url('edit-tags.php'));

        $this->add('taxonomy', $taxonomy);

        if ($post_type) {
            $this->add('post_type', $post_type);
        }
    }
}