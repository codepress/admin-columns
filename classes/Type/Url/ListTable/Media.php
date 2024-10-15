<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\Url\ListTable;

class Media extends ListTable
{

    public function __construct(string $page = null)
    {
        parent::__construct('upload.php');

        $this->add_arg('mode', 'list');

        if ($page) {
            $this->add_arg('page', $page);
        }
    }
}