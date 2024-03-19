<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class LinkToMenuFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new LinkToMenu(
            'on' === $config->get('link_to_menu'),
            $specification
        );
    }

}