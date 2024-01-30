<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class StringLimitFactory implements SettingFactory
{

    public static function create(Config $config, Specification $specification = null): Column
    {
        return new StringLimit(
            $config->has('string_limit') ? $config->get('string_limit') : 'word_limit',
            new SettingCollection([
                (new CharacterLimitFactory())->create($config, StringComparisonSpecification::equal('character_limit')),
                (new WordLimitFactory())->create($config, StringComparisonSpecification::equal('word_limit')),
            ])
        );
    }

}