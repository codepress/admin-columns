<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class WordsPerMinuteFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new WordsPerMinute(
            $config->has('words_per_minute') ? (int)$config->get('words_per_minute') : 200,
            $specification
        );
    }

}