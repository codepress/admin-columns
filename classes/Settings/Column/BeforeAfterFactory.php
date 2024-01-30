<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class BeforeAfterFactory implements SettingFactory
{

    public static function create(Config $config, Specification $specification = null): Column
    {
        return new BeforeAfter(
            $config->has('before') ? (string)$config->get('before') : null,
            $config->has('after') ? (string)$config->get('after') : null
        );
    }

}