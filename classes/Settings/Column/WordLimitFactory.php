<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

final class WordLimitFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new Column\WordLimit(
            $config->has('word_limit') ? (int)$config->get('word_limit') : null,
            $specification
        );
    }

}