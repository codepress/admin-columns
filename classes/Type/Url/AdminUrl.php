<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Uri;

final class AdminUrl extends Uri
{

    public function __construct(string $path)
    {
        parent::__construct((string)admin_url($path));
    }
}