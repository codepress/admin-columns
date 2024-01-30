<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class CharacterLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new Column\CharacterLimit(
            $config->has('character_limit') ? (int)$config->get('character_limit') : null,
            $specification
        );
    }

}