<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

// TODO implement formatter with '<span class="cpac_use_icons"></span>'

class ActionIconsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new ActionIcons(
            'on' === $config->get('use_icons'),
            $specification
        );
    }

}