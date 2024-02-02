<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

// TODO implement formatter with '<span class="cpac_use_icons"></span>'

class ActionIconsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new ActionIcons($config->has('use_icons') && $config->get('use_icons') === 'on', $specification);
    }

}