<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Url;

class PluginSearch implements Url
{

    private $search;

    public function __construct(string $search)
    {
        $this->search = $search;
    }

    public function get_url(): string
    {
        return add_query_arg(
            [
                'tab'  => 'search',
                'type' => 'term',
                's'    => $this->search,
            ],
            admin_url('plugin-install.php')
        );
    }

}