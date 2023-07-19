<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;

class PluginSearch implements QueryAware
{

    use QueryAwareTrait;

    private $search;

    public function __construct(string $search)
    {
        $this->set_url(admin_url('plugin-install.php'));
        $this->add([
            'tab'  => 'search',
            'type' => 'term',
            's'    => $search,
        ]);
    }

    public function __toString(): string
    {
        return $this->get_url();
    }

}