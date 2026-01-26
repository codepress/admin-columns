<?php

declare(strict_types=1);

namespace AC\Type\Url\ListTable;

use AC\Type\Uri;

class Media extends Uri
{

    public function __construct(?string $page = null)
    {
        parent::__construct((string)admin_url('upload.php'));

        $this->add('mode', 'list');

        if ($page) {
            $this->add('page', $page);
        }
    }
}