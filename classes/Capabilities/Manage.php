<?php

declare(strict_types=1);

namespace AC\Capabilities;

use AC\Capabilities;
use WP_Roles;

class Manage extends Capabilities
{

    public function set_default_caps(WP_Roles $roles): void
    {
        $roles->add_cap('administrator', self::MANAGE);
    }

}