<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class WordLimitFactory implements SettingFactory
{

    public static function create(Config $config, Specification $specification = null): Column
    {
        return new Column\WordLimit(
            $config->has('word_limit') ? (int)$config->get('word_limit') : null,
            $specification
        );
    }

}