<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

class NumberFormatFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        // TODO Tobias are the defaults not ok? The typecasting should do the trick?
        return new NumberFormat(
            $config->has('number_format') ? (string)$config->get('number_format') : '',
            $config->has('number_decimals') ? (int)$config->get('number_decimals') : 2,
            $config->has('number_decimal_point') ? (string)$config->get('number_decimal_point') : '',
            $config->has('number_thousands_separator') ? (string)$config->get('number_thousands_separator') : '',
            $specification
        );
    }

}