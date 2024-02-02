<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class PostFormatIconFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new PostFormatIcon(
            $config->get('use_icon') ?: 'off',
            $specification
        );
    }

}