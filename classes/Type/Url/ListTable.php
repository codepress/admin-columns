<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\ListScreenId;
use AC\Type\Uri;

class ListTable extends Uri
{

    public function __construct(string $path, ListScreenId $list_id = null)
    {
        parent::__construct(admin_url($path));

        if ($list_id) {
            $this->add_arg('layout', (string)$list_id);
        }
    }
}