<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\Uri;

class Post extends Uri
{

    public function __construct(string $post_type)
    {
        parent::__construct((string)admin_url('edit.php'));

        $this->add('post_type', $post_type);
    }

}