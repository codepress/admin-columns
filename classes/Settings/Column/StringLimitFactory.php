<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

final class StringLimitFactory implements SettingFactory
{

    public static function create(Config $config, Specification $specification = null): Column
    {
        return new StringLimit(
            $config->get('string_limit') ?: '',
            new SettingCollection([
                CharacterLimitFactory::create(
                    $config,
                    StringComparisonSpecification::equal('character_limit')
                ),
                WordLimitFactory::create(
                    $config,
                    StringComparisonSpecification::equal('word_limit')
                ),
            ]),
            $specification
        );
    }

}