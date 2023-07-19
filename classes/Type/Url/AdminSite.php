<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;

class AdminSite implements QueryAware
{

    use QueryAwareTrait;

    public function __construct(string $path)
    {
        $this->url = admin_url($path);
    }

    public function __toString(): string
    {
        return $this->get_url();
    }

}