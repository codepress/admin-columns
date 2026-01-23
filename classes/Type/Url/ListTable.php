<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Uri;

class ListTable extends Uri
{

    public function __construct(string $path)
    {
        parent::__construct((string)admin_url($path));
    }
}