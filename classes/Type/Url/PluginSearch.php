<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Uri;

class PluginSearch extends Uri
{

    public function __construct(string $search)
    {
        parent::__construct((string)admin_url('plugin-install.php'));

        $this->add('tab', 'search');
        $this->add('type', 'term');
        $this->add('s', $search);
    }

}