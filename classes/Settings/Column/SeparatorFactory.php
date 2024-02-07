<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class SeparatorFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Separator(
            $config->get('separator'),
            $specification
        );
    }

}