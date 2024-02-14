<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

final class WordLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new WordLimit(
            $config->has('excerpt_length') ? (int)$config->get('excerpt_length') : null,
            $specification
        );
    }

}