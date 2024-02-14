<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

final class CharacterLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new CharacterLimit(
            (int)$config->get('character_limit') ?: null,
            $specification
        );
    }

}