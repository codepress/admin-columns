<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Settings\Component;
use AC\Settings\Setting;

// TODO can it extend?
class ImagesFactory implements Settings\SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Images();
    }

}