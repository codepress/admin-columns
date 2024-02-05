<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class WidthFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new Width(
            $config->has('width') ? (int)$config->get('width') : null,
            $config->get('width_unit')
        );
    }

}