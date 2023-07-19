<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type;
use AC\Type\ListScreenId;

class ListTableNetwork implements Type\QueryAware
{

    use Type\QueryAwareTrait;

    public function __construct(string $path, ListScreenId $list_id = null)
    {
        $this->url = network_admin_url($path);

        if ($list_id) {
            $this->add_one('layout', (string)$list_id);
        }
    }

    public function __toString(): string
    {
        return $this->url;
    }
}