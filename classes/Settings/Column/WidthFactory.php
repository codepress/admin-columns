<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class WidthFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new Width(
            $config->has('width') ? (int)$config->get('width') : null,
            $config->has('width_unit') ? (string)$config->get('width_unit') : null
        );
    }

}