<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class NumberOfItemsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new NumberOfItems(
            $config->has('number_of_items') ? $config->get('number_of_items') : 10,

            $specification
        );
    }

}