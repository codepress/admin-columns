<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

final class CharacterLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new CharacterLimit(
            $config->has('character_limit') ? (int)$config->get('character_limit') : null,
            $specification
        );
    }

}