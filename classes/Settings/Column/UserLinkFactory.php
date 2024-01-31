<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings;
use AC\Settings\Column;

class UserLinkFactory implements Settings\SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new UserLink(
            (string)$config->get('user_link_to')
        );
    }

}