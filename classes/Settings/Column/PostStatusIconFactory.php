<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings;
use AC\Settings\Component;

class PostStatusIconFactory implements Settings\SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new PostStatusIcon(
            '1' === $config->get('use_icon'),
            $specification
        );
    }

}