<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Uri;

class PluginSearch extends Uri
{

    public function __construct(string $search)
    {
        parent::__construct(admin_url('plugin-install.php'));
        $this->add_arg('tab', 'search');
        $this->add_arg('type', 'term');
        $this->add_arg('s', $search);
    }

}