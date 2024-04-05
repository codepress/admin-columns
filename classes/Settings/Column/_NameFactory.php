<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

class NameFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Control(
            new Input(
                'name',
                'hidden',
                (string)$config->get('name')
            ),
            ''
        );
    }

}